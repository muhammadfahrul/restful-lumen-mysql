<?php

namespace App\Http\Controllers;

use App\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Image;

class ItemController extends Controller
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

    //
    public function showAll()
    {
        $data = Item::all();
        if(!$data) {
            return response()->json([
                "message" => "Data not found"
            ]);
        }

        return response()->json($data);
    }

    public function showId($id)
    {
        $data = Item::find($id);
        if(!$data) {
            return response()->json([
                "message" => "Parameter Not Found"
            ]);
        }

        return response()->json($data);
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image',
            'name' => 'required|string',
            'amount' => 'required|string',
            'price' => 'required|string',
        ]);

        $data = new Item();
        $data->name = $request->input('name');
        $data->amount = $request->input('amount');
        $data->price = $request->input('price');
        $image = $request->file('image');

        if(!empty($image)){
            $rand = bin2hex(openssl_random_pseudo_bytes(100)).".".$image->extension();
            $rand_md5 = md5($rand).".".$image->extension();
            $data->image = $rand_md5;

            $image->move(storage_path('images'),$rand_md5);
        }
        // $image = Str::random(20);
        // $request->file('image')->move(storage_path('images'), $image);
        // $data->image = $image;
        $data->save();

        return response()->json([
            "message" => "Success Added"
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required|image',
            'name' => 'required|string',
            'amount' => 'required|string',
            'price' => 'required|string',
        ]);
        
        $data = Item::find($id);
        if($data) {
            $data->name = $request->input('name');
            $data->amount = $request->input('amount');
            $data->price = $request->input('price');
            $image = $request->file('image');

            if(!empty($image)){
                $rand = bin2hex(openssl_random_pseudo_bytes(100)).".".$image->extension();
                $rand_md5 = md5($rand).".".$image->extension();
                $data->image = $rand_md5;

                $image->move(storage_path('images'),$rand_md5);
            }
            // $image = Str::random(20);
            // $data->image = $image;
            // $current_avatar_path = storage_path('images') . '/' . $data->image;
            // if (file_exists($current_avatar_path)) {
            //     unlink($current_avatar_path);
            // }
            $data->save();

            return response()->json([
                "message" => "Success Updated"
            ]);
        }else {
            return response()->json([
                "message" => "Parameter Not Found"
            ]);
        }        
    }

    public function delete($id)
    {
        $data = Item::find($id);
        
        if ($data->image) {
            unlink(storage_path('images/' . $data->image));
        }

        if($data) {
            $data->delete();

            return response()->json([
                "message" => "Success Deleted"
            ]);
        }else {
            return response()->json([
                "message" => "Parameter Not Found"
            ]);
        }        
    }
}
