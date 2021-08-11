<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NearByStoresRequest;
use App\Http\Resources\NearByStoreResource;
use App\Models\Product;
use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CustomerController extends AbstractController
{
    public function nearbyStores(NearByStoresRequest $request)
    {
        $stores = Store::selectRaw("stores.*,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$request['lat'], $request['lng'], $request['lat']])
            ->having("distance", "<", 2500)
            ->with('products')
            ->get();
        return response()->json([
            'success'=>true,
            'stores'=>NearByStoreResource::collection($stores)
        ],200);
    }
    public function buyProduct(Product $product){
        // after bank response
        Transaction::create([
            'product_id'=>$product->id,
            'user_id'=>auth()->user()->id,
            'price'=>$product->price,
            'product_name'=>$product->name,
        ]);
        return $this->sendResponse(compact('product'),'transaction is successful');
    }
}
