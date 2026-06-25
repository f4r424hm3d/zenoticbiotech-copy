<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $email = strtolower(trim($request->string('email')));
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($request->string('password'), $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        $token = $user->createToken('admin')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim((string) $validated['email']));
        $user = User::where('email', $email)->where('role', 'admin')->first();

        if ($user) {
            $token = Password::broker()->createToken($user);
            $frontendUrl = rtrim((string) env('FRONTEND_URL', 'https://www.zenoticbiotech.com'), '/');
            $resetUrl = "{$frontendUrl}/admin/reset-password?token={$token}&email=".urlencode($email);

            Mail::html(
                view('emails.admin-password-reset', [
                    'user' => $user,
                    'resetUrl' => $resetUrl,
                ])->render(),
                function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('Reset your Zenotic Biotech admin password');
                }
            );
        }

        return response()->json([
            'message' => 'If an admin account exists for this email, a password reset link has been sent.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $email = strtolower(trim((string) $validated['email']));
        $table = config('auth.passwords.users.table', 'password_reset_tokens');
        $record = DB::table($table)->where('email', $email)->first();
        $expiresAt = $record?->created_at
            ? Carbon::parse($record->created_at)->addMinutes((int) config('auth.passwords.users.expire', 60))
            : null;

        if (! $record || ! Hash::check((string) $validated['token'], $record->token) || ! $expiresAt || $expiresAt->isPast()) {
            return response()->json(['error' => 'This password reset link is invalid or expired.'], 422);
        }

        $user = User::where('email', $email)->where('role', 'admin')->first();
        if (! $user) {
            return response()->json(['error' => 'This password reset link is invalid or expired.'], 422);
        }

        $user->forceFill([
            'password' => Hash::make((string) $validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table($table)->where('email', $email)->delete();
        $user->tokens()->delete();

        return response()->json(['message' => 'Password reset successfully. You can sign in now.']);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = $request->user();
        if (! $user || ! Hash::check((string) $validated['current_password'], $user->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 422);
        }

        $user->forceFill([
            'password' => Hash::make((string) $validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        $currentTokenId = $user->currentAccessToken()?->id;
        $user->tokens()
            ->when($currentTokenId, fn ($query) => $query->where('id', '!=', $currentTokenId))
            ->delete();

        return response()->json(['message' => 'Password changed successfully.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => new UserResource($request->user())]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['success' => true]);
    }
}
