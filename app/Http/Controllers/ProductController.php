<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    protected $product;

    public function __construct()
    {
        $this->middleware('auth');

        $this->product = new Product;
    }

    public function getAll()
    {
        $products = $this->product->get()->toArray();
        try {
            return response()->json($products, 200);
        } catch (Exception $err) {
            return response()->json([
                'success' => false,
                'msg' => $err->getmsg()
            ], 500);
        }
    }


    public function create(Request $request)
    {
        if (Gate::allows('access-users', auth()->user())) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => $validator->msgs()->toArray()
                ], 400);
            }

            try {

                $product = new Product;
                $product->name = $request->input('name');
                $product->price = $request->input('price');
                $product->description = $request->input('description');
                $product->file_path = $request->file('file')->store('products');

                $product->save();


                return response()->json([
                    'success' => true,
                    'msg' => $product
                ], 201);
            } catch (Exception $err) {
                return response()->json([
                    'success' => false,
                    'msg' => $err->getmsg()
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'msg' => "Authorization failed"
            ], 403);
        }
    }


    public function getSingle($id)
    {

        $result = Product::find($id);
        if ($result) {
            return response()->json(['result' =>
            $result], 200);
        } else {
            return response()->json(["msg" => "Product doesn't exist"], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('access-users', auth()->user())) {
            $product = $this->product->find($id);

            if (is_null($product)) {
                return response()->json([
                    'success' => false,
                    'msg' => "Product doesn't exist"
                ], 400);
            }

            try {
                $product->update($request->all());

                return response()->json([
                    'success' => true,
                    'msg' => $product,
                ], 200);
            } catch (Exception $err) {
                return response()->json([
                    'success' => false,
                    'msg' => $err->getmsg()
                ], 500);
            }
        } else {
            return response()->json([
                'success' => false,
                'msg' => "Authorization failed"
            ], 403);
        }
    }

    public function delete($id)
    {
        if (Gate::allows('access-user', auth()->user())) {
            $result = Product::where('id', $id)->delete();

            if ($result) {
                return response()->json(["msg" => "Product has been deleted"], 200);
            } else {
                return response()->json(["msg" => "Product doesn't exist"], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'msg' => "Authorization failed"
            ], 403);
        }
    }
}
