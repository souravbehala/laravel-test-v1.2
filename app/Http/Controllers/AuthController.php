<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Image;
use DB;



class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signup(Request $request){

        $validateData = $request->validate([
            'email' => 'required|unique:users|max:255',
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        $data = array();
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);
        $data['user_role'] = 'admin';
        $data['image'] = 'null';
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $data['username'] = $request->username;

        DB::table('users')->insert($data);

        return $this->login($request);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    public function update(Request $request){
        $user_id = auth()->user()->id;

        $image = $request->file('image');
        if(isset($image)){
            $name_gen = hexdec(uniqid()).".".$image->getClientOriginalExtension();
            Image::make($image)->resize(256,256)->save('image/'.$name_gen);
            $save_url = 'image/'.$name_gen;
        }else{
            $save_url = 'nul';
        }
        

            $data = array();
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $data['username'] = $request->username;
            $data['image'] = $save_url;

            $update = DB::table('users')->where('id', $user_id)->update($data);

            if($update){
               return "Updated succelfully"; 
            }else{
                mysqli_error($update);
            }


    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
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