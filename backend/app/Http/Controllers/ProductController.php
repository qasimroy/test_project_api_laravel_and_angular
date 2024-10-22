<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryNames = $request->query('category');
        $tagNames = $request->query('tag');

        $categoryNames = $categoryNames ? explode(',', $categoryNames) : [];
        $tagNames = $tagNames ? explode(',', $tagNames) : [];

        $products = Product::with('categories', 'tags')
            ->when(count($categoryNames), function ($query) use ($categoryNames) {
                return $query->whereHas('categories', function ($q) use ($categoryNames) {
                    $q->whereIn('name', $categoryNames);
                });
            })
            ->when(count($tagNames), function ($query) use ($tagNames) {
                return $query->whereHas('tags', function ($q) use ($tagNames) {
                    $q->whereIn('name', $tagNames);
                });
            })
            ->get();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::with('categories', 'tags')->findOrFail($id);
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|string|max:255',
            'description1' => 'required|string',
            'description2' => 'required|string',
            'link' => 'required|url',
            'photo' => 'required|url',
            'rating' => 'required|numeric|min:0|max:5',
            'categories' => 'required|array',
            'tags' => 'required|array',
        ]);

        $product = Product::create($validatedData);

        $product->categories()->sync($validatedData['categories']);
        $product->tags()->sync($validatedData['tags']);

        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validatedData = $request->validate([
            'product_name' => 'sometimes|string|max:255',
            'description1' => 'sometimes|string',
            'description2' => 'sometimes|string',
            'link' => 'sometimes|url',
            'photo' => 'sometimes|url',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'categories' => 'sometimes|array',
            'tags' => 'sometimes|array',
        ]);

        $product->update($validatedData);

        if ($request->has('categories')) {
            $product->categories()->sync($validatedData['categories']);
        }

        if ($request->has('tags')) {
            $product->tags()->sync($validatedData['tags']);
        }

        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
