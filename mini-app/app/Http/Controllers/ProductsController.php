<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all products (for API). Consider pagination for production.
        return response()->json(products::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return response()->json([
        'message' => 'Send POST to /api/products with name, price and stock (store endpoint).'
    ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0',
        ]);

        $product = products::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'data'    => $product->only(['name','price','stock']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        try {
            $product = products::find($products->id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }

        return response()->json([
            'name'  => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, products $products)
    {

        try {
            $product = products::find($products->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }

        $validated = $request->validate([
            'price' => 'sometimes|numeric|min:1',
            'stock' => 'sometimes|integer|min:0',
        ]);
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->price = $product->price;
        $request->stock = $product->stock;

        $product->update($validated);

        return response()->json([
            'name'  => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $products)
    {
        try {
            $product = products::find($products->id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
