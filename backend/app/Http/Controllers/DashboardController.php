<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'tags'])->paginate(10);
        $categories = Category::all();
        $tags = Tag::all(); 
        return view('dashboard.dashboard', compact('products', 'categories', 'tags'));
    }
    public function create()
    {
        $imgurClientId = env('IMGUR_CLIENT_ID');

        $categories = Category::all();
        $tags = Tag::all();

        return view('dashboard.create', compact('imgurClientId', 'categories', 'tags'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'description1' => 'nullable|string',
            'description2' => 'nullable|string',
            'link' => 'nullable|url',
            'photo' => 'nullable|string', 
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Product::create([
            'product_name' => $request->product_name,
            'description1' => $request->description1,
            'description2' => $request->description2,
            'link' => $request->link,
            'photo' => $request->photo,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }
}
