<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Reponse;
use Illuminate\Http\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = new User;
    }

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => $validator->messages()->toArray()
            ], 400);
        }

        $check_email = $this->user->where('email', $request->email)->count();
        if ($check_email > 0) {
            return response()->json([
                'msg' => 'User Account already exists'
            ], 400);
        }

        $user = $this->user->create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return $this->login($request);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->only('email', 'password'),
            [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => $validator->messages()->toArray(),
            ], 400);
        }

        $jwt_token = null;

        $input = $request->only('email', 'password');

        if (!$jwt_token = auth('users')->attempt($input)) {
            return response()->json([
                'success' => false,
                'msg' => 'Invalid Credentials',
            ], 400);
        }
        return response()->json([
            'success' => true,
            'token' => $jwt_token
        ], 200);
    }

    public function getUser(Request $request)
    {
        try {
            $user_token = $request->token;
            $user = auth('users')->authenticate($user_token);
            $user_id = $user->id;

            $findUser = $this->user::find($user_id);

            return response()->json([
                'success' => true,
                'user' => $findUser
            ], 200);
        } catch (Exception $err) {
            return response()->json([
                'success' => false,
                'error' => $err->getMessage()
            ], 500);
        }
    }
}
