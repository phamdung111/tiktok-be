<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PersonalCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PersonalController extends Controller
{
    function getPerson($id){
        try{
            $user = DB::table('users')->where('id',$id)->get();
            return response()->json(new PersonalCollection($user));
        }catch(\Exception $e){
            return response()->json(['error' =>$e->getMessage()],400);
        }
    }
}
