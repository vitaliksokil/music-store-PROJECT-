<?php


namespace App\Http\Traits;


use App\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

trait CategoriesProductsTrait
{
    function listCategoriesWithProducts(Array $categories)
    {
        static $products = [];

        foreach ($categories as $category) {

            $categoryData = Category::with(['children','products'])->where('id', $category['id'])->first();

            $categoryChildren = $categoryData->children->toArray();
            $categoryProducts = $categoryData->products->toArray();
            if($categoryProducts){
                if(is_array($categoryProducts)){
                    foreach ($categoryProducts as $prod){
                        $products[] = $prod;
                    }
                }
            }
            if($categoryChildren){
                $this->listCategoriesWithProducts($categoryChildren);
            }

        }
        $products = array_unique($products,SORT_REGULAR);
        return $products;
    }


    public function getProductsByCategory(Request $request, $paginate = true){

        $data = Category::with(['children','products'])->where('id',$request->id)->get()->toArray(); // categories with 1 level of children
        $categories = $this->listCategoriesWithProducts($data); // creating infinite levels of children, children of children!!!
        //sort params
        if(Input::get('order') == 'asc' && Input::get('sort')){
            $categories = collect($categories);
            $categories = array_values($categories->sortBy(Input::get('sort'))->toArray());
        }else{
            $categories = collect($categories);
            $categories = array_values($categories->sortByDesc(Input::get('sort'))->toArray());
        }
        if($paginate){
            $page = Input::get('page', 1); // Get the ?page=1 from the url
            $perPage = 30; // Number of items per page
            $offset = ($page * $perPage) - $perPage;

            return new LengthAwarePaginator(
                array_slice($categories, $offset, $perPage, true), // Only grab the items we need
                count($categories), // Total items
                $perPage, // Items per page
                $page, // Current page
                ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
            );
        }else{
            return $categories;
        }
    }
}
