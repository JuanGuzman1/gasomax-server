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
        $user = User::with('modules')->where('email', $request->email)->first();
        if (!$user) {
            return $this->errorResponse('Not user found');
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
        }
    }

    public function logout()
    {
        try {

            Auth::logout();

            return $this->successResponse(null, 'Sesión finalizada.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getUser()
    {
        $user = Auth::user();
        $userData = User::with(['department', 'modules'])->find($user->id);

        return $this->successResponse($userData);
    }
}
