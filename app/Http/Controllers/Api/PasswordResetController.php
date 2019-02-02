<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PasswordResetController extends ApiController
{
    //
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->responseError('We can\' t find a user with that e-mail address.', [], 422);
        }

        if (!$user->email_verified_at) {
            return $this->responseError('Account not verified!', [], 404);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60),
            ]
        );
        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
            return $this->responseSuccess('We have e-mailed your password reset link!');
        }
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset) {
            return $this->responseError('This password reset token is invalid.', [], 422);
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return $this->responseError('This password reset token has expired.', [], 422);
        }
        return $this->responseSuccess('This password reset token is valid.');
    }
    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'password' => 'required|string|confirmed ',
            'token' => 'required|string ',
        ]);

        if ($validator->fails()) {
            return $this->responseError('Error de validación!', $validator->errors()->toArray(), 422);
        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token]
        ])->first();
        if (!$passwordReset) {
            return $this->responseError('This password reset token is invalid.');
        }
        $user = User::where('email', $passwordReset->email)->first();

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return $this->responseSuccess('Successfully new password set!');
    }
}
