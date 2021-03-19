<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $r){
        $validate=Validator::make($r->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if( $validate->fails()){
            return response()->json(['status_code'=>400,'message'=>'bad Request']);
        }
        $user=new User();
        $user->name=$r->name;
        $user->email=$r->email;
        $user->password=bcrypt($r->password);
        $user->save();
        return response()->json([
            'status_code'=>200,
            'message'=>'user register success',

        ]);

    }
    public function login(Request $r){
        $validate=Validator::make($r->all(),[
            
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if( $validate->fails()){
            return response()->json(['status_code'=>400,'message'=>'bad Request']);
        }
        $credentails=request(['email','password']);
        if(!Auth::attempt($credentails)){
            return response()->json(['status_code'=>500,'message'=>'unable']);
        }
        $user=User::where('email',$r->email)->first();
        $tokenResult=$user->createToken('authToken')->plainTextToken;
        return response()->json([
            'status_code'=>200,
            'token'=>$tokenResult
        ]);
    }
    public function logout(Request $r){   
        $r->user()->currentAccessToken()->delete();
        return response()->json([
            'status_code'=>200,
            'message'=>'token deleted',        ]);
    }
}
