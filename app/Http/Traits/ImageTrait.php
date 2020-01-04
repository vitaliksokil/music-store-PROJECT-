<?php


namespace App\Http\Traits;


trait ImageTrait
{
    public function convertImageName($photo, $path){
        $name = time() . '.' . explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];
        //save image to folder
        \Image::make($photo)->save(public_path('images/'.$path) . $name);
        return $name;
    }
}
