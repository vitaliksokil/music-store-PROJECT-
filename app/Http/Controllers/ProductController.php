<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {

    }

    public function search(){
        if($search = \Request::get('q')){
            $products =Product::where(function ($query) use ($search){
                $query->where('id','LIKE',"%$search%")
                    ->orWhere('title','LIKE',"%$search%")
                    ->orWhere('price','LIKE',"%$search%")
                    ->orWhere('description','LIKE',"%$search%");
            })->get();
        }else{
            $products = $this->getProducts();
        }
        return $products;
    }

    public function create(Request $request)
    {
        $this->authorize('isAdmin');
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required|numeric',
            'photo' => 'required',
            'category' => 'required'
        ]);
        $this->convertImageName($request);

        $product = Product::create($request->except('category'));
        $product->category()->attach($request->category);

        return response(['messageType' => 'success', 'message' => 'New product has been successfully created!!!']);
    }

    public function convertImageName(&$request)
    {
        $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
        \Image::make($request->photo)->save(public_path('images/products/') . $name);
        $request->merge(['photo' => $name]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('isAdmin');

        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required|numeric',
            'photo' => 'required',
            'category' => 'required'
        ]);
        $product = Product::find($id);

        if (strlen($request->photo) > 50) {
            $this->convertImageName($request);
            $image_path = public_path() . '/images/products/' . $product->photo;
            @unlink($image_path); // deleting photo
        }

        if ($product->update($request->except('category'))) {
            $product->category()->detach();
            $product->category()->attach($request->category);
            return response(['messageType' => 'success', 'message' => 'Product was updated successfully']);

        } else {
            return response(['messageType' => 'error', 'message' => 'Something went wrong']);

        }
    }

    public function getProductByID($id)
    {
        $this->authorize('isAdmin');

        return  Product::with('category:id')->where('id',$id)->get();
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $product = Product::find($id);
        $image_path = public_path() . '/images/products/' . $product->photo;
        @unlink($image_path); // deleting photo
        $product->delete();


    }

    public function show($id)
    {

    }

    public function getProducts()
    {
        // todo remove the line below if it would be necessary
        $this->authorize('isAdmin');
        $products = Product::all();
        $productsWithCategories = [];
        $i = 0;
        foreach ($products as $product) {
            $productsWithCategories[$i] = $product->toArray();
            $productsWithCategories[$i]['category_title'] = $product->category->first()->title;
            $i++;
        }
        return $productsWithCategories;
    }

    public function getCurrentProductByID($id)
    {
        return Product::findOrFail($id);
    }
}
