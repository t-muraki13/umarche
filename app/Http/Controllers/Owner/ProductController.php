<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use App\Models\PrimaryCategory;
use App\Models\Owner;
use App\Models\Shop;
use App\Models\Stock;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Constants\Common;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('products');
            if (!is_null($id)) {
                $productsOwnerId = Products::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId;
                if ($productId !== Auth::id()) {
                    abort(404);
                }
            }

            return $next($request);
        });
    }

    public function index()
    {
        //$products = Owner::findOrFail(Auth::id())->shop->product;
        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id', Auth::id())->get();

        //dd($ownerInfo);

        // foreach($ownerInfo as $ownerInfos) {
        //     //dd($ownerInfos->shop->product);
        //     foreach($ownerInfos->shop->product as $product) {
        //         dd($product->imageFirst->filename);
        //     }
        // }

        return view('owner.products.index',
        compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        // dd($shops);

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();

        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.create',
        compact('shops', 'images', 'categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        dd($request);
        try{
            DB::transaction(function () use($request) {
                $product = Products::create([
                    'name' => $request->name,
                    'infomation' => $request->infomation,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity
                ]);
            }, 2);
        } catch(Throwable $e){
            Log::error($e);
            throw $e;
        }

        
        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品登録しました。',
        'status' => 'info']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        //dd($product);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        //dd($shops);

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();

        //dd($images);

        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.edit',
        compact('product', 'quantity', 'shops', 'images', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        //dd($request);
        $request->validate([
            'current_quantity' => ['required', 'integer'],
        ]);
        //dd($request);
        $product = Products::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');
        //dd($request->current_quantity, $quantity);
        if($request->current_quantity !== strval($quantity)) {
            //dd($request->route());
            $id = $request->route()->parameter('product');
            //dd($id);
            return redirect()->route('owner.products.edit', ['product' => $id])
            ->with(['message' => '在庫数が変更されています。再度確認してください。','status' => 'alert']);
        } else {
            //dd($product);
            try{
                DB::transaction(function () use($request, $product) {
                    $product->name = $request->name;
                    $product->infomation = $request->infomation;
                    $product->price = $request->price;
                    $product->sort_order = $request->sort_order;
                    $product->shop_id = $request->shop_id;
                    $product->secondary_category_id = $request->category;
                    $product->image1 = $request->image1;
                    $product->image2 = $request->image2;
                    $product->image3 = $request->image3;
                    $product->image4 = $request->image4;
                    $product->is_selling = $request->is_selling;
                    $product->save();

                    if($request->type === Common::PRODUCT_LIST['add']) {
                        $newQuantity = $request->quantity;
                    }
                    if($request->type === Common::PRODUCT_LIST['reduce']) {
                        $newQuantity = $request->quantity * -1;
                    }
                    Stock::create([
                        'product_id' => $product->id,
                        'type' => $request->type,
                        'quantity' => $newQuantity
                    ]);
                }, 2);
            } catch(Throwable $e){
                Log::error($e);
                throw $e;
            }

            return redirect()
            ->route('owner.products.index')
            ->with(['message' => '商品情報を更新しました。',
            'status' => 'info']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Products::findOrFail($id)->delete();

        return redirect()
        ->route('owner.products.index')
        ->with(['message' => '商品を削除しました。', 'status' => 'alert']);
    }
}
