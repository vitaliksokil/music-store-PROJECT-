<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Traits\CategoriesProductsTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use CategoriesProductsTrait;

    public function search(){
        if($search = \Request::get('q')){
            $categories =Category::where(function ($query) use ($search){
                $query->where('id','LIKE',"%$search%")
                    ->orWhere('title','LIKE',"%$search%");
            })->get();
        }else{
            $categories = $this->index();
        }
        return $categories;
    }

    function list_categories(Array $categories)
    {
        $data = [];

        foreach ($categories as $category) {
            $categoryChildren = Category::with('children')->where('id', $category['id'])->get()->toArray()[0]['children'];
            $data[] = [
                'id' => $category['id'],
                'title' => $category['title'],
                'parent_id' => $category['parent_id'],
                'photo' => $category['photo'],
                'children' => $this->list_categories($categoryChildren),
            ];
        }

        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $data = Category::with('children')->orderBy('parent_id')->get()->toArray(); // categories with 1 level of children
        $categories = $this->list_categories($data); // creating infinite levels of children, children of children!!!
        return $categories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
        $request->validate([
            'title' => 'required|string',
            'photo' => 'required',

        ]);
        $this->convertImageName($request);

        Category::create($request->all());
        return response(['messageType' => 'success', 'message' => 'New category has been successfully created!!!']);
    }

    public function convertImageName(&$request)
    {
        $name = time() . '.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
        \Image::make($request->photo)->save(public_path('images/categories/') . $name);
        $request->merge(['photo' => $name]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Category $category
     * @return Category|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function show($category)
    {

        return Category::with('children')->where('id', $category)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Category $category)
    {

        $this->authorize('isAdmin');

        $request->validate([
            'title' => 'required',
        ]);

//todo duplicate fix
        if (strlen($request->photo) > 50) {
            $this->convertImageName($request);
            $image_path = public_path() . '/images/categories/' . $category->photo;
            @unlink($image_path); // deleting photo
        }
        if ($category->update($request->all())) {
            dd($request->all());
            return response(['messageType' => 'success', 'message' => 'Category was updated successfully']);

        } else {
            return response(['messageType' => 'error', 'message' => 'Something went wrong']);

        }
    }


    public function deleteChildren(&$children)
    {
        foreach ($children as $child) {
            $childrenOfChild = Category::with('children')->where('id', $child['id'])->first()->children;
            $this->deleteChildren($childrenOfChild);

            $image_path = public_path() . '/images/categories/' . $child->photo;
            @unlink($image_path); // deleting photo

            //deleting photos
            $this->deletePhotos($child->products());
            //deleting products
            $child->products()->delete();

            $child->delete();
        }
    }

    public function deletePhotos($products){
        $products = $products->get();
        foreach ($products as $product) {
            $image_path = public_path() . '/images/products/' . $product->photo;
            @unlink($image_path); // deleting photo
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $category = Category::with('children')->where('id', $id)->first();

        $image_path = public_path() . '/images/categories/' . $category->photo;
        @unlink($image_path); // deleting photo

        $this->deleteChildren($category->children);

        $this->deletePhotos($category->products());
        $category->products()->delete();
        $category->delete();
    }



}
