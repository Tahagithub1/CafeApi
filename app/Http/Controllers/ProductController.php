<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
           'success' => true,
            'products' => $products,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:25|unique:categories,name',
            'image' => 'required|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id'],
            [
                'category_id.exists' => 'The Selected Category is Invalid , Please Choose a Valid Category'
            ],
            [
            'description' => 'string|required|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }else{

            $product = Product::create([
                'title' => $request->input('title'),
                'image' => $imagePath,
                'category_id' => $request->input('category_id'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Product Created Successfully',
                'category' => $product
            ], 201);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->json([
                'success' => false ,
                'message' => 'Product not Found!'
            ],404);
        }else{
            return response()->json([
                'success' => true ,
                'product' => $product
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->json([
                'success' => false ,
                'message' => 'Product not Found!'
            ],404);
        }else{
            $product->delete();
            return response()->json([
                'success' => true ,
                'message' => 'Product Deleted Successfully!'
            ],200);
        }

    }
}
