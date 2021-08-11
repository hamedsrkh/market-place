<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AbstractRequest;
use App\Http\Requests\Api\CreateProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use App\Http\Resources\ProductAllResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends AbstractController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        $store = auth()->user()->store;
        if (!$store) {
            return $this->sendError('no store is defined for you - please contact with support');
        }
        $products = $store->products()->latest()->get();
        return response()->json([
            'success' => true,
            'data' => ProductAllResource::collection($products)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $store = auth()->user()->store;
        if (!$store) {
            return $this->sendError('no store is defined for you - please contact with support');
        }
        $product = $store->products()->create($request->only('name','price'));
        return $this->sendResponse(compact('product'),'product has created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        $product->load('store','store.user');
        return response()->json([
            'success' => true,
            'data' => ProductAllResource::make($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {

        $product->update($request->only('name','price'));
        return $this->sendResponse(compact('product'),'product has updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse(null,'products has deleted successfully');

    }
}
