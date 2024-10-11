<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryConrtoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'categories' => $categories,
        ], 200);

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
            'name' => 'required|string|max:22|unique:categories,name',
            'image' => 'required|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
//      $request->validate([
//         'name' => 'required|string|max:22',
//         'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
//      ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }
//      $category = new Category();
//      $category->name = $request->input('name');
//      $category->image = $imagePath;
//      $category->save();
//
//            return response()->json([
//                'message' => 'Category Created Successfully',
//                'category' => $category
//            ],201);
             $category = Category::create([
                'name' => $request->input('name') ,
                'image' => $imagePath
             ]);
        return response()->json([
            'success' => true,
            'message' => 'Category Created Successfully',
            'category' => $category
        ], 201);

    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category){
            return response()->json([
                'success' => false ,
                'message' => 'Category not Found!'
            ],404);
        }else{
            return response()->json([
                'success' => true ,
                'category' => $category
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
       $category = Category::find($id);
       if (!$category){
           return response()->json([
             'success' => false ,
             'message' => 'Category not Found!'
           ],404);
       }else{
           $category->delete();
           return response()->json([
               'success' => true ,
               'message' => 'Category Deleted Successfully!'
           ],200);
       }


    }
}
