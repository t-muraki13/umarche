<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use App\Models\Stock;
use App\Models\Products;
use Stripe\Stripe;
use App\Constants\Common;
use Stripe\Checkout\Session;
use App\Services\CartService;
use App\Jobs\SendThanksMail;
use App\Jobs\SendOrderedMail;

class CartController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        //dd($user->products);
        $products = $user->products;
        $totalPrice = 0;

        //dd($products);
        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        //dd($totalPrice, $products);

        return view('user.cart', compact('products', 'totalPrice'));
    }

    public function add(Request $request)
    {
        $itemInCart = Cart::where('product_id', $request->product_id)
        ->where('user_id', Auth::id())->first();

        if($itemInCart) {
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('user.cart.index');

    }

    public function delete($id)
    {
        Cart::where('product_id', $id)
        ->where('user_id', Auth::id())
        ->delete();

        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        
        $lineItems = [];
        foreach($products as $product) {
            $quantity = '';
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');
            //dd($product->pivot->quantity, $quantity, $product->id);
            if($product->pivot->quantity > $quantity){
                //dd('uu');
                return redirect()->route('user.cart.index');
            } else {
                // $lineItem = [
                //     'name' => $product->name,
                //     'description' => $product->infomation,
                //     'amonut' => $product->price,
                //     'currency' => 'jpy',
                //     'quantity' => $product->pivot->quantity,
                // ];
                // array_push($lineItems, $lineItem);
                $lineItem = [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $product->name,
                            'description' => $product->infomation,
                        ],
                        'unit_amount' => $product->price * 100, // 金額をセント単位で設定（円の場合も小数点以下の値を含める必要がある場合は100で掛ける）
                    ],
                    'quantity' => $product->pivot->quantity,
                ];
                array_push($lineItems, $lineItem);
            }
        }
        //dd('yyy');
        foreach($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => Common::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        //dd('test');

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[$lineItems]],
            'mode' => 'payment',
            'success_url' => route('user.cart.success'),
            'cancel_url' => route('user.cart.cancel'),
        ]);

        //dd($session);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout',
        compact('session', 'publicKey'));
    }

    public function success()
    {
        ////
        $items = Cart::where('user_id', Auth::id())->get();
        $products = CartService::getItemsInCart($items);
        //dd($products);
        $user = User::findOrFail(Auth::id());

        SendThanksMail::dispatch($products, $user);
        foreach($products as $product) {
            SendOrderedMail::dispatch($product, $user);
        }
        //dd('ユーザーメール送信テスト');

        ////

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.items.index');
    }

    public function cancel()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        foreach($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => Common::PRODUCT_LIST['add'],
                'quantity' => $product->pivot->quantity
            ]);
        }

        return redirect()->route('user.cart.index');
        
    }

}
