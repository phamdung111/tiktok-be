<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loggedInUser()
    {
        try {
            $user = User::where('id',auth()->user()->id)->get();
            return response()->json(new UsersCollection($user),200);
        }
        catch (\Exception $e) { 
            return response()->json(['error' => $e->getMessage()],400);
        }
    }

    public function updateUserImage(Request $request)
    {
        $request->validate(['image' => 'required|mimes:png,jpg,jpeg']);
        if ($request->height === '' || $request->width === '' || $request->top === '' || $request->left === '') {
            return response()->json(['error' => 'The dimensions are incomplete'], 400);
        }

        try {
            $user = (new FileService)->updateImage(auth()->user(), $request);
            $user->save();

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateUser(Request $request) {
        $request->validate(['name'=> 'required']);

        try {
            $user = User::find(auth()->user()->id);
            $user->name = $request->input('name');
            $user->bio = $request->input('bio');
            $user->save();
            return response()->json(['status'=> 'success'],200);
        }catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 400);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
