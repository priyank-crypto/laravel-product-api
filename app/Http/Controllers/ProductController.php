<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(
            Product::with('category')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        $product = Product::create($validated);
        return new ProductResource( $product->load('category') );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource( $product->load('category') );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255', 
            'price' => 'sometimes|numeric', 
            'description' => 'nullable|string', 
            'category_id' => 'sometimes|exists:categories,id' 
        ]);
        $product->update($validated);

        return new ProductResource( $product->load('category') );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([ 'message' => 'Product deleted successfully' ]);
    }
}
