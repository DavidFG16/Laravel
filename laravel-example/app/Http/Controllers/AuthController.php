<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisteredMail;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * 
     * @OA\Post(
     * path="/api/auth/login",
     * tags=("Auth"),
     * summary ="Login y con creacion de token"
     * 
     * @OA\RequestBody(
     *      required_true,
     *      @OA\JsonContent(
     *      required ={"email", "password"},
     *          @OA\Property(
     *          property="email", type="string", format="email", example="user@gmail.com"
     *          ),
     *          @OA\Property(
     *          property="password", type"string", minlenght=8, example="pa55Word1235."
     *          )
     *      ),
     * @OA\Response(
     *     response=200, description="OK",
     *      @OA\JsonContent(
     *      @OA\Property(property="status", type="string", example="success")
     *      @OA\Property(property="message", type="string", example="Todo ok ccomo dijo el pibe")
     *      @OA\Property(property="data", type="object", 
     *          @OA\Property(property="token_type", type="string", example="Bearer")
     *          @OA\Property(property="access_token", type="string", example="eyJ0121sadb..."),
     *      ),
     *      @OA\Property(property="", type="", example="")
     *      ) 
     * ),
     * @OA\Response(response=401, description="Usuario o contraseña no invalidas")
     * )
     * )
     * 
     */
    function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        //!Auth::attempt($data)
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Credenciales invalidas', 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('api-token', ['posts.read', 'posts.write']);

        $token = $tokenResult->accessToken;

        Mail::to($user->email)->queue(new UserRegisteredMail($user)); //Queue

        return $this->success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => [
                'email' => $user->email,
                'roles' => $user->roles()->pluck('name'),
            ]
        ]);
    }

    function signup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $defaultRole = Role::where('name', 'viewer')->first();
        if ($defaultRole) {
            $user->roles()->syncWithoutDetaching([$defaultRole->id]);
        }
        return $this->success($user->load('roles'), 'Usuario creado correctamente', 201);
    }

    function me(Request $request)
    {
        return $this->success("Hellou Camper!");
    }

    function logout(Request $request)
    {
        return $this->success("Hellou Camper!");
    }
}