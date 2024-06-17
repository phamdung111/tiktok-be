<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PeopleCollection;

class PeopleController extends Controller
{
    function getPerson($id){
        try{
            $user = DB::table('users')->where('id',$id)->get();
            if(count($user) == 0){
                return response()->json(["status"=>"not found"],404);
            }
            return response()->json(new PeopleCollection($user),200);
        }catch(\Exception $e){
            return response()->json(['error' =>$e->getMessage()],400);
        }
    }
}
