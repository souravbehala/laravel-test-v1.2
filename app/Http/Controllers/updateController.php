<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Mail;

use Illuminate\Http\Request;

class updateController extends Controller
{
   public function update($id){
    $update = DB::table('users')->where('remember_token', $id)->first();
    return view('update', compact('update'));
   }

   public function edit(Request $request, $id){
        $otp = rand(123456, 999999);
        $data = array();
        $data['username'] = $request->username;
        $data['password'] = Hash::make($request->password);
        $data['updated_at'] = Carbon::now();

         $update = DB::table('users')->where('remember_token', $id)->first();

         $mail = $update->email;

           $request->session()->put('data',$data);
           $request->session()->put('id',$id);
           $request->session()->put('otp', $otp); 

           $e_data = ['otp'=> $otp];

            Mail::send('send_otp', $e_data, function($massege) use ($mail){
              $massege->to($mail);
              $massege->subject('genarate password');
             });

           return redirect()->route('otp');

   }
}
