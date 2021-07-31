<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Mail;


class createUser extends Controller
{


     public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    
    public function createUser(Request $request){

        $user_role = auth()->user()->user_role;

        if($user_role == "admin"){
            $email_token = rand();
            $validateData = $request->validate([
            'email' => 'required|unique:users|max:255',
            'name' => 'required',
            'password' => 'required|min:6',
            ]);
 
            $data = array();
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $data['user_role'] = $request->user_role;
            $data['remember_token'] = $email_token;
            $data['username'] = "null";
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $data['image'] = "null";


            $user =  DB::table('users')->insert($data);

            $mail = $data['email'];

            $e_data = ['link'=>"http://127.0.0.1:8000/update/".$email_token];

            Mail::send('mail', $e_data, function($massege) use ($mail){
                $massege->to($mail);
                $massege->subject('genarate password');
                });
            return "Invitation send";

            }else{
                return "You are not admin";
            }

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'name' => auth()->user()->name,
            'user_id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'user_role' => auth()->user()->user_role,

        ]);
    }

    
}