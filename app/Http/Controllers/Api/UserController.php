<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function updateProfile(Request $request){
        $user = auth()->user();
        if (!Hash::check(request('currentPassword'), $user->password)) {
            return response(['messageType' => 'error', 'message' => 'Incorrect password']);
        }
        if($request->name || !$request->newPassword){
            $request->validate([
                'name' => 'min:3|max:50',
            ]);
            $user->name = trim($request->name);
        }else if($request->newPassword && !$request->name){
            $request->validate([
                'newPassword' => 'min:6',
            ]);
            $user->password = bcrypt($request->newPassword);
        }else{
            $request->validate([
                'name' => 'min:3|max:50',
                'newPassword' => 'min:6',
            ]);
            $user->name = trim($request->name);
            $user->passowrd = bcrypt($request->newPassword);
        }
        $user->save();
        return response(['messageType' => 'success', 'message' => 'Your data was successfully updated']);

    }
    public function updateEmail(Request $request){
        $user = User::whereEmail($request->email)->first();
        if(!$user){
            return response(['messageType' => 'error', 'message' => 'User not found']);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response(['messageType' => 'error', 'message' => 'Incorrect password']);
        }
        $request->validate([
            'newEmail' => 'email|required',
        ]);
        if($user->email == $request->newEmail){
            return response(['messageType' => 'error', 'message' => 'Your new email is the same as your current email']);
        }
        $user->email_verified_at = NULL;
        $user->email = $request->newEmail;
        $user->save();

        return response(['messageType' => 'success', 'message' => 'Email was successfully changed!!! Please go to your new email address and follow the instruction to verify your account']);
    }
}
