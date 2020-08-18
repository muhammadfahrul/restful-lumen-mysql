<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

use Image;

class UserController extends Controller
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

    public function showAll()
    {
        $data = User::all();
        if(!$data) {
            return response()->json([
                "message" => "Data Not Found"
            ]);
        }

        return response()->json([
            "message" => "Success retrieve data",
            "status" => true,
            "data" => $data
        ]);
    }

    public function showId($id)
    {
        $data = User::find($id);
        if(!$data) {
            return response()->json([
                "message" => "Parameter Not Found"
            ]);
        }

        return response()->json([
            "message" => "Success retrieve data",
            "status" => true,
            "data" => $data
        ]);
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $data = new User();
        $data->name = $request->input('name');
        $data->email = $request->input('email');
        $data->password = Hash::make($request->input('password'));
        $data->api_token = Hash::make($data->name);
        $data->save();

        return response()->json([
            "message" => "Success Added",
            "status" => true,
            "data" => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        
        $data = User::find($id);
        if($data) {
            $data->name = $request->input('name');
            $data->email = $request->input('email');
            $data->password = Hash::make($request->input('password'));
            $data->api_token = Hash::make($data->name);
            $data->save();

            return response()->json([
                "message" => "Success Updated",
                "status" => true,
                "data" => $data
            ]); 
        }else {
            return response()->json([
                "message" => "Parameter Not Found"
            ]);
        }        
    }

    public function delete($id)
    {
        $data = User::find($id);
        if($data) {
            $data->delete();

            return response()->json([
                "message" => "Success Deleted",
                "status" => true,
                "data" => $data
            ]);   
        }else {
            return response()->json([
                "message" => "Parameter Not Found"
            ]);
        }        
    }
}
