<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    public function index(){
        $product = Product::latest()->paginate(10);
        return response()->json([
            'status' => true,
            'message' => "Product List",
            'data' => [
                'product' => $product
            ]
        ]);
    }
    public function store(Request $request){
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
                ], 422);
        }

        $product = Product::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'stock' => $request['stock']
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }

    public function show($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status' => false,
                'message' => "Product not found"
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Product details",
            'data' => $product
        ]);
    }
}
