<?php

namespace App\Http\Controllers;

use App\User;

use Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Image;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    public function login(Request $request, User $user)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $api_token = $this->jwt($user);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                "message" => "Login Failed"
            ]);
        }

        if (Hash::check($password, $user->password)) {
            $user->api_token = $api_token;
            $user->save();

            return response()->json([
                "message" => "Login Success",
                "users" => $user,
            ]);
        }else {
            return response()->json([
                "message" => "Invalid Email or Password"
            ]);
        }
    }

}
