<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {
    use ApiResponser;

    protected $rules = [
        'username' => 'required|email',
        'password' => 'required|min:6'
    ];

    public function register(Request $request) {
        // TODO: Do better password validation

        $validator = Validator::make($request->json()->all(), $this->rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $username = $request->json()->get('username');
        $password = $request->json()->get('password');

        $checkEmail = DB::table('users')->where('username', $username)->first();

        if ($checkEmail) {
            return $this->errorResponse(array('username' => ['Email already exists']), Response::HTTP_CONFLICT);
        }

        $register = DB::table('users')->insert([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => 0
        ]);

        if ($register) {
            Log::info("[AuthController][register] - New User {$username}");
            return $this->successResponse('User registered successfully', Response::HTTP_CREATED);
        }
        Log::error('[AuthController][register][Database Error] - User could not be registered');
        return $this->errorResponse('User could not be registered', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function login (Request $request) {
        $validator = Validator::make($request->json()->all(), $this->rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $request->json()->get('username');
        $password = $request->json()->get('password');

        $user = DB::table('users')->where('username', $email)->first();

        if (!$user) {
            return $this->errorResponse(array('username' => ['User does not exist']), Response::HTTP_NOT_FOUND);
        }

        if (!Hash::check($password, $user->password)) {
            return $this->errorResponse(array('password' => ['Incorrect Password']), Response::HTTP_UNAUTHORIZED);
        }

        $payload = [
            'email' => $user->username,
            'role' => $user->role,
            'user_id' => $user->user_id,
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        Log::info("[AuthController][login] - User {$email} logged in");
        return $this->successResponse(['token' => "Bearer {$jwt}"], Response::HTTP_OK);
    }

    public function isSuperAdmin(Request $request) {
        $user = $request->user();

        if ($user->role == 4) {
            return true;
        }
        return false;
    }

    public function addRestaurantUser(Request $request) {
        if (!$this->isSuperAdmin($request)) {
            return $this->errorResponse('You are not authorized to perform this action', Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->json()->all(), $this->rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $username = $request->json()->get('username');
        $password = $request->json()->get('password');

        $checkEmail = DB::table('users')->where('username', $username)->first();

        if ($checkEmail) {
            return $this->errorResponse(array('username' => ['Email already exists']), Response::HTTP_CONFLICT);
        }

        $register = DB::table('users')->insert([
            'username' => $username,
            'password' => Hash::make($password),
            'role' => 1
        ]);

        if ($register) {
            Log::info("[AuthController][addRestaurantUser] - New Restaurant User {$username}");
            return $this->successResponse('User registered successfully', Response::HTTP_CREATED);
        }

        Log::error('[AuthController][addRestaurantUser][Database Error] - User could not be registered');
        return $this->errorResponse('User could not be registered', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getAllUsers(Request $request) {
        if (!$this->isSuperAdmin($request)) {
            return $this->errorResponse('You are not authorized to perform this action', Response::HTTP_UNAUTHORIZED);
        }

        return DB::table('users')->select('user_id', 'username', 'role')->get();
    }
}