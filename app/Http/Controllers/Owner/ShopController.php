<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            ///dd($request->route()->parameter('shop'));
            //dd(Auth::id());
            $id = $request->route()->parameter('shop');
            if (!is_null($id)) {
                $shopOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopOwnerId;
                $ownerId = Auth::id();
                if ($shopId !== $ownerId) {
                    abort(404);
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        //$ownerId = Auth::id();
        //dd($ownerId);
        $shops = Shop::where('owner_id', Auth::id())->get();

        //dd($shops);

        return view('owner.shops.index',
        compact('shops'));
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        //dd(Shop::findOrFail($id));
        return view('owner.shops.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $imageFile = $request->image;
        if (!is_null($imageFile) && $imageFile->isValid()) {
            Storage::putFile('public/shops', $imageFile);
        }

        return redirect()->route('owner.shops.index');
    }
}
