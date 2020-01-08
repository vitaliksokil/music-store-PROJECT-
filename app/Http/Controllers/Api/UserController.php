<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
            $user->password = bcrypt($request->newPassword);
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


    public function search()
    {
        if($search = \Request::get('q')){
            $users =User::where(function ($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                    ->orWhere('email','LIKE',"%$search%")
                    ->orWhere('id','LIKE',"%$search%")
                    ->orWhere('type','LIKE',"%$search%");
            })->paginate(10);
        }else{
            $users= User::latest()->paginate(10);

        }
        return $users;
    }

    public function update(Request $request)
    {

        $this->authorize('isAdmin');

        $user = User::findOrFail($request->id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password' => 'min:6'
        ]);

        $user->name = trim($request->name);
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->type = $request->type;
        $user->email_verified_at = $request->email_verified_at;
        $user->save();


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');

        return User::latest()->paginate(5);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'password' => Hash::make($request['password']),
            'email_verified_at' => $request['email_verified_at'],
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        $user->delete();
    }

    public function user($id){
        return User::findOrFail($id);
    }

}
