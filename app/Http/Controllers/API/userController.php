<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;
use Carbon\Carbon;
class userController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login() {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('tfran')-> accessToken; 
            $userApp = new user;
            $type= $userApp::where('email',request('email'))->value('type');
            return response()->json(['success' => $success,'type' => $type], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        }
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
        ]);
		if ($validator->fails()) { 
		            return response()->json(['error'=>$validator->errors()], 401);            
		        }
		        $input = $request->all(); 
                $input['password'] = bcrypt($input['password']);

                 
                // $user = User::create($input); 
                $user = new user;
                $user->name = $input['name'];
                $user->email = $input['email'];
                $user->password = $input['password'];
                $user->type = 0;
                $user->save();
		        $success['token'] =  $user->createToken('tfran')-> accessToken; 
		        $success['name'] =  $user->name;
		return response()->json(['success'=>$success], $this-> successStatus); 
    }
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
    public function logout(Request $req) 
    {
    	$accessToken = $req->data;
    	DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken)
            ->delete();

        // $accessToken->revoke();
        return response()->json(['t' => $accessToken]);
    }

    public function showit() {
        return response()->json(['msg' => 'sss']);
    }
}
