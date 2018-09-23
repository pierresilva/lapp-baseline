<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\SignupActivate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->save();

        $avatar = \Avatar::create($user->name)->getImageObject()->encode('png');
        \Storage::disk('public')->put('avatars/' . $user->id . '/avatar.png', (string) $avatar);

        $this->sendActivationCode($user);

        return response()->json([
            'message' => 'Successfully created user!',
        ], 201);
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
        $activationToken = str_random(60);

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
                'message' => 'Credentials do not match!',
                'errors' => [
                    'invalid_credentials' => 'Please verify your credentials',
                ],
            ], 401);
        }

        if (!auth()->user()->email_verified_at) {
            \Auth::logout();
            return response()->json([
                'message' => 'Account not verified!',
                'errors' => [
                    'account_not_verified' => 'The account has not been verified yet',
                ],
            ], 401);
        }

        if (!auth()->user()->active) {
            \Auth::logout();
            return response()->json([
                'message' => 'Account inactive!',
                'errors' => [
                    'account_inactive' => 'The account is inactive',
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
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
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
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
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
                'message' => 'Invalid activation code!',
                'errors' => [
                    'activation_code_invalid' => 'This activation token is invalid',
                ],
            ], 404);
        }
        $user->active = true;
        $user->activation_token = null;
        $user->email_verified_at = now();
        $user->save();
        return $user;
    }
}
