<?php

namespace App\Http\Controllers;

use App\Category;
use App\Feedback;
use App\Http\Traits\CategoriesProductsTrait;
use App\Http\Traits\ImageTrait;
use App\Product;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Traits\LikesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    use LikesTrait, CategoriesProductsTrait, ImageTrait;

    private $elementsPerPage = 10;

    public function __construct()
    {

    }

    public function search()
    {
        $sort = Input::get('sort');
        $order = Input::get('order');
        if ($search = \Request::get('q')) {
            $products = Product::with('category:title')->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%$search%")
                    ->orWhere('title', 'LIKE', "%$search%")
                    ->orWhere('price', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            })->orderBy($sort,$order)->get();
        } else {
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
        $request['photo'] = $this->convertImageName($request->photo, 'products/');
        $product = Product::create($request->except('category'));
        $product->category()->attach($request->category);

        return response(['messageType' => 'success', 'message' => 'New product has been successfully created!!!']);
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
            $request['photo'] = $this->convertImageName($request->photo, 'products/');
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

        return Product::with('category:id')->where('id', $id)->get();
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
        $products = Product::with('category:title')->latest()->paginate($this->elementsPerPage);
        return $products;
    }

    public function getCurrentProductByID($id)
    {
        $product = Product::with('feedbacks.user:name,id', 'feedbacks.likes')->findOrFail($id);
        $product->productRate = DB::table('feedbacks')->where('product_id', $product->id)->avg('rate');
        // counting likes and dislikes
        // dividing one likes array into likes and dislikes!!!
        $this->divisionToLikesDislikes($product->feedbacks);
        return $product;
    }

    public function getRecommendedProducts($id)
    {
        // recommended products - it's some random products from the same category!!!
        // if there is not many products in that category then take from the others

        $numberOfRecommendedProducts = 10;

        $categoryID = Product::find($id)->category->first()->id;
        $category = Category::find($categoryID);
        $products = $category->products;
        while (count($products) < $numberOfRecommendedProducts && $category->parent_id) {
            $products = $this->getProductsByCategory(new Request(['id' => $category->parent_id]),false);
            $category = Category::find($category->parent_id);
        }
        if (count($products) > $numberOfRecommendedProducts) {
            $newProducts = [];
            $randomElementsKeys = array_rand($products, $numberOfRecommendedProducts);
            foreach ($randomElementsKeys as $randomKey) {
                $newProducts[] = $products[$randomKey];
            }
            shuffle($newProducts);
            $this->deleteCurrentProduct($newProducts, $id); // deleting current product from recommended
            return $newProducts;
        }
        if(is_object($products)) {
            $products = $products->toArray();
        }
        shuffle($products);
        $this->deleteCurrentProduct($products, $id); // deleting current product from recommended
        return $products;

    }

    private function deleteCurrentProduct(&$products, $currentProductID)
    {
        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i]['id'] == $currentProductID) {
                unset($products[$i]);
                $products = array_values($products);
                break;
            }
        }
    }

    public function getNewProducts(){
        $products = Product::latest()->take(10)->get();
        return $products;
    }

    public function random(){
        $products = Product::inRandomOrder()->take(9)->get();
        return $products;
    }


}
