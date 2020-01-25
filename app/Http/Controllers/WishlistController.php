<?php

namespace App\Http\Controllers;

use App\ShoppingCart;
use App\Wishlist;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // $id - Product id
    public function create(Request $request)
    {
        try{
            Wishlist::create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
            ]);
        }catch (QueryException $exception){
            abort(500,'Error. This product probably is already in your shopping cart!!!');
        }
    }

    // $id - Product id
    public function delete($product_id)
    {
        $shoppingCartItem = Wishlist::where('product_id', $product_id)->first();
        $shoppingCartItem->delete();
    }

    public function show()
    {
        $shoppingCart = Wishlist::with('product')->where('user_id', Auth::user()->id)->get()->pluck('product');
        return $shoppingCart;
    }

    public function count()
    {
        $count = Wishlist::where('user_id', Auth::user()->id)->count();
        return $count;
    }

    public function isAlreadyInWishlist($product_id)
    {
        $product = Wishlist::where([
            ['product_id', $product_id],
            ['user_id', Auth::user()->id]
        ])->first();
        $isAlreadyInShopCart = $product ? true : false;
        return response(['result' => $isAlreadyInShopCart]);
    }

    public function deleteAll()
    {
        if (Wishlist::where('user_id', Auth::user()->id)->delete()) {
            return response(['message' => 'Successfully removed!!!', 'status' => 'success']);
        } else {
            return response(['message' => 'An error occurred!!!', 'status' => 'error']);
        }
    }

    public function addToShoppingCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);
        // adding to shopping cart
        try {
            ShoppingCartController::create($request);
            // deleting this prod from wishlist
            if (
            Wishlist::where([
                ['product_id', $request->product_id],
                ['user_id', Auth::user()->id]
            ])->delete()
            ) {
                return response(['message' => 'Successfully added to your shopping cart', 'status' => 'success']);
            } else {
                abort('400', 'An error occurred!!!');
            }
        } catch (QueryException $exception) {
            abort('400', 'This product is already in your shopping cart!!!');
        }


    }

    public function addAllToShoppingCart()
    {
        $wishlistToDelete = Wishlist::where('user_id', Auth::user()->id);
        $wishlist = $wishlistToDelete->get()->toArray();
        try {
            if (ShoppingCart::insert($wishlist)) {
                $wishlistToDelete->delete();
                return response([
                    'message' => 'All products from your wishlist are moved to your shopping cart',
                    'status' => 'success'
                ]);
            } else {
                abort('400', 'Something went wrong!!! Be sure that all of your products are not in shopping cart.');
            }
        }catch (QueryException $exception){
            abort('400', 'Something went wrong!!! Be sure that all of your products are not in shopping cart.');
        }
    }
}
