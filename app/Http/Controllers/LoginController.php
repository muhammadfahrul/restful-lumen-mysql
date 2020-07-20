<?php

namespace App\Http\Controllers;

use App\User;

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

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $api_token = Hash::make($email);

        $check_user = User::where('email', $email)->first();

        if (!$check_user) {
            return response()->json([
                "message" => "Login Failed"
            ]);
        }

        if (Hash::check($password, $check_user->password)) {
            $check_user->api_token = $api_token;
            $check_user->save();

            return response()->json([
                "message" => "Login Success",
                "api_token" => $api_token,
                "users" => $check_user,
            ]);
        }else {
            return response()->json([
                "message" => "Invalid Email or Password"
            ]);
        }
    }

}
