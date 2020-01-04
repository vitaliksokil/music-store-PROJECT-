<?php

namespace App\Http\Controllers;

use App\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingCartController extends Controller
{
    // $id - Product id
    static public function create(Request $request){
        ShoppingCart::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
        ]);
    }


    // $id - Product id
    public function delete($product_id){
        $shoppingCartItem = ShoppingCart::where('product_id', $product_id)->first();
        $shoppingCartItem->delete();
    }

    public function show(){
        $shoppingCart = ShoppingCart::with('product')->where('user_id', Auth::user()->id)->get()->pluck('product');
        return $shoppingCart;
    }

    public function count(){
        $count = ShoppingCart::where('user_id', Auth::user()->id)->count();
        return $count;
    }

    public function isAlreadyInShoppingCart($product_id){
        $product = ShoppingCart::where([
            ['product_id',$product_id],
            ['user_id',Auth::user()->id]
        ])->first();
        $isAlreadyInShopCart = $product ? true : false;
        return response(['result' => $isAlreadyInShopCart]);
    }
    public function deleteAll(){
        if(ShoppingCart::where('user_id', Auth::user()->id)->delete()){
            return response(['message' => 'Successfully removed!!!', 'status'=>'success']);
        }else{
            return response(['message' => 'An error occurred!!!', 'status'=>'error']);
        }
    }
}
