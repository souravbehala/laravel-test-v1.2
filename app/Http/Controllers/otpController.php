<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class otpController extends Controller
{
   public function index(){
    return view ('otp');
       }

       public function verify(Request $request){
        $otp = $request->otp;

        $verify = $request->session()->get('otp');

        if($otp == $verify){

        $data = $request->session()->get('data');
        $id = $request->session()->get('id');

        $update = DB::table('users')->where('remember_token', $id)->update($data);

        return "Now login with your email and password using postman";
        }


       }
}
