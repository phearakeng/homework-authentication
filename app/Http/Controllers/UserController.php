<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    |-========================================================================-
        { - FUNCTION CREATE USER - }
    |-========================================================================-
    */
        public function signup(Request $request)
        {
            $request->validate([
                'password'=> 'required|confirmed',
            ]);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $token = $user->createToken('CuteToken')->plainTextToken;
            $UsersCreate = "Sign up successfully...";
            return response()->json([
                'Message' => $UsersCreate,
                'user' => $user,
                'token' => $token,
            ]);
        }

     /*
    |-========================================================================-
        { - FUNCTION SIGN OUT USER - }
    |-========================================================================-
    */
        public function signout(Request $request) {
            auth()->user()->tokens()->delete();
            return response()->json(['Message' => "Sign out Successfully..."]);
        }

     /*
    |-========================================================================-
        { - FUNCTION LOGIN USER - }
    |-========================================================================-
    */
        public function login(Request $request) {

            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['Message' => 'Incorrec password!'], 401);
            }

            $token = $user->createToken('CuteToken')->plainTextToken;
            $confirm = "Login successfully...";

            return response()->json([
                'Message' => $confirm,
                'user' => $user,
                'token' => $token,
            ]);
        }
}
