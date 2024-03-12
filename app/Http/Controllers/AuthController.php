<?php

namespace App\Http\Controllers;

use App\Models\Users\User;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;


class AuthController extends Controller
{
    use ApiResponseTrait;

    public function login(Request $request)
    {
        $user = User::with(['department', 'modules', 'permissions'])->where('email', $request->email)->first();
        if (!$user) {
            return $this->errorResponse('Usuario o contraseña incorrecto');
        }

        if (Hash::check($request->password, $user->password)) {
            $tokenCreated = $user->createToken('authToken');

            $data = [
                'access_token' => $tokenCreated->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenCreated->token->expires_at)->toDateTimeString(),
                'user' => $user
            ];
            return $this->successResponse($data);
        } else {
            return $this->errorResponse('Usuario o contraseña incorrecto');
        }
    }

    public function logout()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $user->tokens->each(function ($token) {
                    $token->delete();
                });
                return $this->successResponse(null, 'Sesión finalizada.');
            }
            return response()->json(['message' => 'No user authenticated'], 401);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getUser()
    {
        $user = Auth::user();
        $userData = User::with(['department', 'role', 'modules', 'permissions', 'files'])->find($user->id);

        return $this->successResponse($userData);
    }
}
