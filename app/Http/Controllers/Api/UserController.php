<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
   public function loggedInUser()
    {
        try {
            $user = User::where('id',auth()->user()->id)->get();
            return response()->json(new UserCollection($user),200);
        }
        catch (\Exception $e) { 
            return response()->json(['error' => $e->getMessage()],400);
        }
    }

    public function updateUserImage(Request $request)
    {
        $request->validate(['image' => 'required|mimes:png,jpg,jpeg']);
        try{
            $image = $request->file('image');
            $name = $image->hashName();
            Storage::putFileAs('avatars', $image, $name);
            DB::table('users')->where('id',auth()->id())->update(['image'=>'/storage/avatars/'.$name]);
            return response()->json(['status'=>'success'],200);
        }catch(\Exception $e) {
            return response()->json(['error'=>$e->getMessage()],400);
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
}
