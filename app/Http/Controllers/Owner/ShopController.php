<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

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

    public function update(UploadImageRequest $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'infomation' => ['required', 'string', 'max:1000'],
            'is_selling' => ['required'],
        ]);

        $imageFile = $request->image;
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $fileNameToStore = ImageService::upload($imageFile, 'shops');
            // Storage::putFileAs('public/' . $folderName . '/' , $file, $fileNameToStore );
        }

        //dd($request);
        $shop = Shop::findOrFail($id);
        $shop->name = $request->name;
        $shop->infomation = $request->infomation;
        $shop->is_selling = $request->is_selling;

        if (!is_null($imageFile) && $imageFile->isValid()) {
            $shop->filename = $fileNameToStore;
        }

        $shop->save();

        return redirect()
        ->route('owner.shops.index')
        ->with(['message' => '店舗情報を更新しました。',
        'status' => 'info']);;
    }
}
