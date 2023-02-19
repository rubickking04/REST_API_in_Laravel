<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use App\Mail\Welcome_Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        $user->tokens()->create([
            'name' => $user->name,
            'token' => hash('sha256', $success['token']),
            'abilities' => ['*'],
        ]);
        $response = [
            'success' => true,
            'data' => $success,
            'message' => "User registered successfully"
        ];
        Mail::to($user->email)->send(new Welcome_Mail($user));
        Auth::login($user);
        return response()->json($response, 200);
    }
}
