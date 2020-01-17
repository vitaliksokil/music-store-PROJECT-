<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        //adding user_id to data
        $order = [];
        foreach ($request->all() as $requestItem){
            $order[] = $requestItem +['user_id'=>Auth::user()->id];
        }
        if (Order::insert($order)) {
            $prods_ids_to_delete = array_map(function($item){ return $item['product_id']; }, $order);
            if(DB::table('shopping_carts')->whereIn('product_id', $prods_ids_to_delete)->where('user_id',Auth::user()->id)->delete()){
                return response(['status' => 'success', 'message' => 'Your order was successfully created. We will contact you to confirm your order!']);
            }
        }

        abort('400');
    }
    public function myOrders(){
        $user_id = Auth::user()->id;
        $orders = Order::with('product')->where('user_id',$user_id)->orderBy('created_at','DESC')->get();
        return $orders;
    }


    public function unverifiedOrders(){
        $this->authorize('isAdmin');
        $orders = Order::select('user_id','created_at')->where('is_verified', NULL)->latest()->get()->groupBy('user_id');
        return $orders;
    }
    public function ordersByUserID($user_id){
        $this->authorize('isAdmin');
        $orders = Order::with('product')->where('user_id',$user_id)->get();
        return $orders;
    }
    public function verifyAllByUserID($user_id){
        $this->authorize('isAdmin');
        if(Order::where('user_id',$user_id)->update(['is_verified' => 1])){
            return response(['message'=>'Every record was successfully updated!!!','status' => 'success']);
        }else{
            return response(['message'=>'An error occurred!','status' => 'error']);
        }

    }
    public function verify($order_id){
        $this->authorize('isAdmin');
        if(Order::where('id',$order_id)->update(['is_verified' => 1])){
            return response(['message'=>'The record was successfully updated!!!','status' => 'success']);
        }else{
            return response(['message'=>'An error occurred!','status' => 'error']);
        }

    }
}
