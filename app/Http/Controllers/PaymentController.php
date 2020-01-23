<?php

namespace App\Http\Controllers;

use App\Order;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $post = $request->all();
        $key = hash('sha256', $post['LMI_PAYEE_PURSE'] .
            $post['LMI_PAYMENT_AMOUNT'] .
            $post['LMI_HOLD'] .
            $post['LMI_PAYMENT_NO'] .
            $post['LMI_MODE'] .
            $post['LMI_SYS_INVS_NO'] .
            $post['LMI_SYS_TRANS_NO'] .
            $post['LMI_SYS_TRANS_DATE'] .
            'EtOdJAjoNb' .
            $post['LMI_PAYER_PURSE'] .
            $post['LMI_PAYER_WM']);
        if (strtoupper($key) != $post['LMI_HASH']) {
            abort(500, 'An error occurred');
        }
        $payments = [];
        foreach ($post['orders_ids'] as $orders_id) {
            $payments[] = [
                'order_id' => $orders_id,
                'amount' => $post['LMI_PAYMENT_AMOUNT'],
                'payer_purse' => $post['LMI_PAYER_PURSE'],
                'payer_wm' => $post['LMI_PAYER_WM'],
                'created_at' => $post['LMI_SYS_TRANS_DATE'],
            ];
        }

        if (Payment::insert($payments)) {
            foreach ($post['orders_ids'] as $orders_id) {
                Order::where('id', $orders_id)->update(['is_paid' => 1]);
            }
            echo response(['message' => 'Thank you for your purchase', 'status' => 'success']);
        }
    }

    public function piecePayment(Request $request){
        $payments = [];
        foreach ($request['orders_ids'] as $orders_id) {
            $payments[] = [
                'order_id' => $orders_id,
                'amount' => $request['amount'],
                'payer_purse' => $request['payer_purse'],
                'payer_wm' => $request['payer_wm'],
            ];
        }
        if (Payment::insert($payments)) {
            foreach ($request['orders_ids'] as $orders_id) {
                Order::where('id', $orders_id)->update(['is_paid' => 1]);
            }
            return response(['message' => 'Thank you for your purchase', 'status' => 'success']);
        }
    }
    public function show(){
        return Payment::all();
    }
}
