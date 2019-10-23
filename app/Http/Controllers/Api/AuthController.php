<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Notifications\SignupActivate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends ApiController
{
    //
    /**
     * Create user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse [string] message
     */
    public function signup(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation errors',
                'response' => [
                    'errors' => $validation->errors()
                ]
            ], 422);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->save();

        $user->assignRole(2);

        $avatar = null; // \Avatar::create($user->name)->getImageObject()->encode('png');

        if ($avatar) {
            \Storage::disk('public')->put('avatars/' . $user->id . '/avatar.png', (string) $avatar);
        }

        $this->sendActivationCode($user);

        return $this->responseSuccess('Successfully created user!', [], 201);
    }

    /**
     * Resend email to verify account
     *
     * @param   Request $request
     * @return  [string] message
     */
    public function resendActivationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->get('email'))->first();

        if (!$user) {
            return response()->json([
                'message' => 'Unregistered user!',
                'errors' => [
                    'unregistered_user' => 'Account does not exist',
                ],
            ], 404);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'message' => 'Verified account!',
                'errors' => [
                    'verified_account' => 'Account has been verified at ' . $user->email_verified_at,
                ],
            ], 422);
        }

        $this->sendActivationCode($user);

        return response()->json([
            'message' => 'Please check your email inbox to verify your account!',
        ], 200);
    }

    /**
     * Send email with activation account url
     *
     * @param User $user
     * @return void
     */
    private function sendActivationCode(User $user)
    {
        $activationToken = Str::random(60);

        $user->activation_token = $activationToken;
        $user->save();

        $notified = $user->notify(new SignupActivate($user));

        if ($notified) {
            return true;
        }

        return false;
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = request(['email', 'password']);

        //$credentials['active'] = 1;
        //$credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 1,
                'response' => [
                    'errors' => [
                        'invalid_credentials' => 'Verifique su email o contraseña.',
                    ],
                ],
                'message' => 'Credenciales no validas!',
            ], 401);
        }

        if (!auth()->user()->email_verified_at) {
            \Auth::logout();
            return response()->json([
                'status' => 1,
                'message' => 'Cuenta no verificada!',
                'response' => [
                    'errors' => [
                        'account_not_verified' => 'La cuenta no ha sido verificada aun.',
                    ]
                ]
            ], 401);
        }

        if (!auth()->user()->active) {
            \Auth::logout();
            return response()->json([
                'message' => 'Cuenta inactiva!',
                'errors' => [
                    'account_inactive' => 'La cuenta se encuentra en estado inactivo.',
                ],
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(4);
        }

        $token->save();
        return response()->json([
            'message' => 'Ingreso con éxito!',
            'status' => 0,
            'response' => [
                'token' => $tokenResult->accessToken,
                'user' => json_encode(auth()->user()),
                'name' => auth()->user()->name,
                'id' => auth()->user()->id,
                'email' => auth()->user()->email,
                'time' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->timestamp,
            ]
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => 0,
            'message' => 'Successfully logged out',
            'response' => []
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json([
            'message' => 'OK',
            'response' => [
                'data' => $request->user(),
            ],
            'status' => 0,
        ]);
    }

    /**
     * Activate use account
     *
     * @param [type] $token
     * @return void
     */
    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => 1,
                'response' => [
                    'errors' => [
                        'activation_code_invalid' => 'This activation token is invalid',
                    ],
                ],
                'message' => 'Invalid activation code!',
            ], 404);
        }
        $user->active = true;
        $user->activation_token = null;
        $user->email_verified_at = now();
        $user->save();
        return response()->json([
            'status' => 0,
            'response' => [
                'data' => $user
            ],
            'message' => 'Successfully activated account!',
        ], 200);
    }
}
