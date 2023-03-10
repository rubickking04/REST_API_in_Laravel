<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    use ThrottlesLogins;
    /**
     * Login a nexisting account in api.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $maxAttempts = 5;
            $decayMinutes = 1;
            $throttleKey = $request->ip() . '|' . $request->email;
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                $minutes = $this->limiter()->availableIn($throttleKey);
                $errors = [
                    'message' => trans('auth.throttle', ['seconds' => $minutes * 60]),
                ];
                return response()->json($errors, 429);
            }
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
                $this->clearLoginAttempts($request);
                return response()->json($errors, 200);
            }
            if (!Auth::attempt(['email' => $request->email])) {
                $this->incrementLoginAttempts($request);
                $errors = [
                    'message' => 'These credentials do not match our records.',
                ];
                return response()->json($errors, 200);
            }
        }
        catch (\Exception $e) {
            $errors = [
                'message' => $e->getMessage(),
            ];
            return response()->json($errors, 500);
        }
    }


    public function index(){
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
