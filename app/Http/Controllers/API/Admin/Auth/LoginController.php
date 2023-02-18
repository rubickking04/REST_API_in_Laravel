<?php

namespace App\Http\Controllers\API\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $user = \App\Models\Admin::where($this->username(), $request->{$this->username()})->first();
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
    }
}
