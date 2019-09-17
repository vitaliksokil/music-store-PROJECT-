<?php

namespace App\Http\Controllers\Api;

use App\Mail\resetPasswordMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Null_;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|min:3|max:50',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'

        ]);
        $user = User::firstOrNew(['email' => $request->email]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $http = new Client;

        $response = $http->post(url('/oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);

        return response(['data' => json_decode((string)$response->getBody(), true), 'user' => $user]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        // Проверяем существует ли пользователь с указанным email адресом
        $user = User::whereEmail(request('email'))->first();

        if (!$user) {
            return response(['status' => 'error', 'field' => 'email', 'message' => 'User not found']);
        }
        // Если пользователь с таким email адресом найден - проверим совпадает
        // ли его пароль с указанным
        if (!Hash::check(request('password'), $user->password)) {
            return response(['status' => 'error', 'field' => 'password', 'message' => 'Incorrect password']);
        }
        // Внутренний API запрос для получения токенов
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();
        // Убедимся, что Password Client существует в БД (т.е. Laravel Passport
        // установлен правильно)
        if (!$client) {
            return response()->json([
                'message' => 'Laravel Passport is not setup properly.',
                'status' => 500
            ], 500);
        }
        $data = [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Проверяем был ли внутренний запрос успешным
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status' => 422
            ], 422);
        }

        // Вытаскиваем данные из ответа
        $data = json_decode($response->getContent());

        // Формируем окончательный ответ в нужном формате
        return response()->json([
            'data' => [
                'token' => $data->access_token,
                'user' => $user,
                'status' => 200
            ]
        ]);
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);

    }
    public function getUser()
    {
        return auth()->user();
    }

    public function sendResetPasswordEmail(Request $request){
        $user = DB::table('users')->where('email',$request->email)->first();
        if($user){
            $token = Str::random(60);
            $resetPassword = DB::table('password_resets')->where('email',$request->email);
            if($resetPassword->first()){
                $resetPassword->update([
                    'token' => $token
                ]);
            }else{
                DB::table('password_resets')->insert([
                    'email'=>$request->email,
                    'token'=>$token,
                ]);
            }
            Mail::to(trim($request->email))->send(new resetPasswordMail($user->name,$token,$request->email));
            return response(['messageType' => 'success','message'=>'Email with instruction to reset your password was successfully sent']);
        }else{
            return response(['messageType'=>'error','message' => 'User with this email not found']);
        }
    }
    public function verifyResetToken(Request $request){
        $token = $request->token;
        $email = $request->email;
        $verifyData = DB::table('password_resets')->where('email',$email)->first();
        if($verifyData){
            if($verifyData->token==$token){
                return response(['messageType'=>'success','message'=>'Token verified!!!']);
            }else{
                return response(['messageType'=>'error','message'=>'Wrong token!!!']);
            }
        }else{
            return response(['messageType'=>'error','message'=>'Wrong email!!!']);
        }
    }
    public function resetPassword(Request $request){

        $request->validate([
            'password' => 'required|min:6',
        ]);

        $user = User::whereEmail(request('email'))->first();
        if($user){
            $verifyData = DB::table('password_resets')->where('email',$request->email)->first();
            if($verifyData){
                if($verifyData->token == $request->token){
                    $user->password = bcrypt($request->password);
                    $user->save();
                    DB::table('password_resets')->where('email',$request->email)->delete();
                    return response(['messageType'=>'updated','message'=>'Password was updated successfully']);
                }else{
                    response(['messageType'=>'error','message'=>'Wrong reset token!!!']);
                }
            }else{
                return response(['messageType'=>'error','message'=>'There is no reset token with this email address!!!']);
            }
        }else{
            return response(['messageType'=>'error','message'=>'Wrong email address!!!']);
        }



    }

}
