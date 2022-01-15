<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)
    {     
        if($request->ismethod('post')){
        $data = $request->all();
    //        //echo "<pre>";print_r($data);die; 
    //     $user = User::create([
    //     'name' => $request->name,
    //     'email' => $request->email,
    //     'password' => Hash::make($request->password),
    // ]);
    //     $token =$user->createToken($user->email. '_token')->plainTextToken;
    //     return response()->json([
    //         'status'=> 200,
    //         'user'=>$user,
    //         'token'=>  $token,
    //         'message'=>'user Added Successfully',
    //     ]);
        //$user = DB::table('users')->get();
        //echo "<pre>";print_r($user);die;
        //echo $password = Hash::make('admin'); die;
        $validator = Validator::make($data,[
            'email'=>'required|email|max:191',
            'password'=>'required|max:10|min:5',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'validate_err'=>$validator->messages(),
            ]);
        }
        else
        { 
            //$user = DB::table('users')->where('email',$data['email'])->first();
            //echo "<pre>";print_r($user);die;
            $user = User::where('email', $request->email)->first();
               $req_password = $data['password'];
            if (! $user || ! Hash::check($req_password, $user->password)) {
                return response()->json([
                    'status'=>401,
                    'message'=>'Invalid Credentials',
                ]);
            }
            
            $token =$user->createToken($user->email. '_token')->plainTextToken;
            return response()->json([
                'status'=> 200,
                'name'=>$user->name,
                'token'=>$token,
                'message'=>'login Successfully',
            ]);
            
 
        }
    }

    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=> 200,
            'message'=>'logout Successfully',
        ]);
    }
}
