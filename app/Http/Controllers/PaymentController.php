<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay()
    {
        $key = hash('sign', $_POST['LMI_PAYEE_PURSE'] .
            $_POST['LMI_PAYMENT_AMOUNT'] .
            $_POST['LMI_HOLD'] .
            $_POST['LMI_PAYMENT_NO'] .
            $_POST['LMI_MODE'] .
            $_POST['LMI_SYS_INVS_NO'] .
            $_POST['LMI_SYS_TRANS_NO'] .
            $_POST['LMI_SYS_TRANS_DATE'] .
            'EtOdJAjoNb' .
            $_POST['LMI_PAYER_PURSE'] .
            $_POST['LMI_PAYER_WM']);
        if (strtoupper($key) != $_POST['LMI_HASH']) {
            exit;
        }

        // todo confirm orders , set payed to true
    }
}
