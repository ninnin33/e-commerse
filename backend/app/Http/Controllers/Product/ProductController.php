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
            'data' =>  $product
        ], 200);
    }
    public function store(Request $request){
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
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
        ], 201);
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
        ], 200);
    }

    public function update(Request $request, $id){
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status' => false,
                'message' => "Product not found"
            ], 404);
        }

        $validated = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ]);

        if($validated->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }

        $product->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    public function destroy($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status' => false,
                'message' => "Product not found"
            ], 404);
        }

        $product->delete();
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully',
            'data' => $product
        ], 200);
    }
}
