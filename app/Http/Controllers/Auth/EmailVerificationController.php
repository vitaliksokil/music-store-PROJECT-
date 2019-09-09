<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\emailVerificationMail;
use Illuminate\Support\Str;
use Carbon\Carbon;
class EmailVerificationController extends Controller
{
    public function sendVerificationEmail()
    {
        $user = auth()->user();
        $token = Str::random(60);
        $user->verification_token = $token;
        $user->save();
        Mail::to(trim($user->email))->send(new emailVerificationMail($user));

        return response(['success' => 'Email was send successfully']);

    }
    public function verify(Request $request){
        $user = auth()->user();
        $token = $request->token;
        if($user->email_verified_at != NULL){
            $user->verification_token = NULL;
            $user->save();
            return response()->json(['messageType'=>'success','message' => 'Email has been already verified']);
        }
        if($user->verification_token == $token){
            $user->verification_token = NULL;
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            $user->save();
            return response()->json(['messageType'=>'success' ,'message' => 'Your email was successfully verified!!! Thank you']);
        }

        return response()->json(['messageType'=>'error','message' => 'Something went wrong, try again','status' => 400]);

    }
}
