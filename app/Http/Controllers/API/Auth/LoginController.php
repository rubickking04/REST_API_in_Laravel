<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Login a nexisting account in api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            $errors = [
                'success' => false,
                'errors' => $validator->errors(),
            ];
            return response()->json($errors, 200);
        }
        $errors = [$this->username() => trans('auth.failed')];
        $user = \App\Models\User::where($this->username(), $request->{$this->username()})->first();
        if ($user && !Hash::check($request->password, $user->password)) {
            $errors = [
                'message' => 'The provided password is incorrect.'
            ];
            return response()->json($errors, 200);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $user = $request->user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            // Add this code to save the token to the database
            $user->tokens()->where('name', 'API Token')->delete();
            $user->tokens()->create([
                'name' => 'API Token',
                'token' => hash('sha256', $success['token']),
                'abilities' => ['*'],
            ]);
            $errors = [
                'success' => true,
                'data' => $success,
                'message' => "Welcome " . $user->name . " to MyApp"
            ];
            return response()->json($errors, 200);
        }
        if (!Auth::attempt(['email' => $request->email])) {
            $errors = [
                'message' => 'These credentials do not match our records.',
            ];
            return response()->json($errors, 200);
        }
        // return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    public function index(){
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
