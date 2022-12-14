<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use function auth;
use function response;

class UserController extends Controller
{
    /**
     * Register
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = "user";
            $user->save();

            $success = true;
            $message = 'User register successfully';
        } catch (QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'user' => $user ?? null,
            'message' => $message,
        ];
        return response()->json($response);
    }

    /**
     * Login
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $success = true;
            $message = 'User login successfully';
        } else {
            $success = false;
            $message = 'Wrong login credentials';
        }

        // response
        $response = [
            'success' => $success,
            'user' => auth()->user() ?? null,
            'message' => $message,
        ];
        return response()->json($response);
    }

    /**
     * Logout
     */
    public function logout(): JsonResponse
    {
        try {
            Session::flush();
            $success = true;
            $message = 'Successfully logged out';
        } catch (QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }

    public function allUsers(): JsonResponse
    {
        $users = User::all();

        return response()->json([
            "users" => $users,
            "message" => "success"
        ]);
    }
}
