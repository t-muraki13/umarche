<?php

namespace App\Services;

use App\Models\Products;
use App\Models\Cart;

class CartService
{

  public static function getItemsInCart($items)
  {
    $products = [];

    //dd($items);
    foreach($items as $item) {
      $p = Products::findOrFail($item->product_id);
      //$owner = $p->shop->owner->select('name', 'email')->first()->toArray();//オーナー情報
      $owner = $p->shop->owner; // ownerまで
      $ownerInfo = [
        'ownerName' => $owner->name,
        'email' => $owner->email
      ];
      //dd($ownerInfo);
      // $values = array_values($owner);//連想配列の値を取得
      // $keys = ['ownerName', 'email'];
      // $ownerInfo = array_combine($keys, $values);
      $productInfo = Products::where('id', $item->product_id)
                 ->select('id', 'name', 'price')->get()->toArray();//商品情報の配列
      $quantity = Cart::where('product_id', $item->product_id)
                  ->select('quantity')->get()->toArray();//在庫数の配列
      $result = array_merge($productInfo[0], $ownerInfo, $quantity[0]);//配列の結合
      array_push($products, $result);//配列の追加

    }
    //dd($products);
    return $products;

  }

}
?>