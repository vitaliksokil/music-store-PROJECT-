<?php

namespace App\Http\Controllers;

use App\Http\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    use ImageTrait;

    public function update(Request $request){
        $this->authorize('isAdmin');
        $request->validate([
            'sitename' => 'required',
            'logo' => 'required',
            'town' => 'required',
            'street' => 'required',
            'phonenumber' => 'required',
            'schedule' => 'required',
            'vk' => 'required',
            'facebook' => 'required',
            'olx' => 'required',
        ]);

        $siteData= DB::table('site')->where('id',1);

        if(strlen($request->logo) > 50){
            $request['logo'] = $this->convertImageName($request->logo,'');
            $image_path = public_path().'/images/' . $siteData->first()->logo;
            @unlink($image_path); // deleting photo
        }

        if($siteData->update($request->all())){
            return response(['messageType' => 'success','message'=>'Site info was updated successfully']);
        }else{
            return response(['messageType' => 'error','message'=>'Something went wrong.Maybe you didn\'t change anything']);

        }
    }
    public function getInfo(){
        return response()->json(DB::table('site')->find(1));
    }
}
