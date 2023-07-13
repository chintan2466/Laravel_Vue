<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MOdels\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\HAsh;

class UserController extends Controller
{
    //
    function index(Request $request)
    {
        // echo '<br /><br /><br /><br /><pre>'; print_r($request); echo '</pre>'; exit;
        $user= User::where('email', $request->email)->first();
        // print_r($user);
            if (!$user || !HAsh::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
        
             return response($response, 201);
    }
}
