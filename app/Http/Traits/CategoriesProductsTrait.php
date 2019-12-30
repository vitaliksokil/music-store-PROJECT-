<?php


namespace App\Http\Traits;


use App\Category;

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


    public function getProductsByCategory($id){
        $data = Category::with(['children','products'])->where('id',$id)->get()->toArray(); // categories with 1 level of children
        $categories = $this->listCategoriesWithProducts($data); // creating infinite levels of children, children of children!!!
        return $categories;
    }
}
