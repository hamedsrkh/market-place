<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\Api\CreateSellerRequest;
use App\Http\Requests\Api\UpdateSellerRequest;
use App\Http\Resources\SellerAllResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerController extends AbstractController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::whereHas('store')->with('store')->latest()->get();
        return response()->json([
            'success'=>true,
            'data'=>SellerAllResource::collection($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSellerRequest $request)
    {
        $user = User::create($request->only('name','password','email'));
        $store = $user->store()->create([
            'name'=>$request->store_name,
            'address'=>$request->address,
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
        ]);
        $user->roles()->attach(Role::where('name', 'seller')->first()->id);
        return $this->sendResponse(compact('user','store'),'store created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = User::where('id',$user->id)->whereHas('store')->with('store')->first();
        if ($user){
            return response()->json([
                'success'=>true,
                'data'=>SellerAllResource::make($user)
            ]);
        }
        return $this->sendError('seller not found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UpdateSellerRequest $request)
    {
        $user->update($request->only('name','password','email'));
        $store = $user->store()->update([
            'name'=>$request->store_name,
            'address'=>$request->address,
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
        ]);
        $user->load('store');
        return $this->sendResponse(compact('user'),'store created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->store()->delete();
        $user->delete();
        return $this->sendResponse(null,'seller deleted successfully');

    }
}
