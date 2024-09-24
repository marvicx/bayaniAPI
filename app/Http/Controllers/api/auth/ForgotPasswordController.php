<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\ForgotPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Get the email input from the request
        $email = $request->input('email');

        // Attempt to find the user by email
        $user = User::where('email', $email)->first();

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        try {
            // Notify the user with the reset password notification
            $user->notify(new ResetPasswordNotification());

            return response()->json(['success' => true, 'message' => 'Password reset email sent successfully!'], 200);
        } catch (Exception $e) {
            Log::error('Failed to send password reset notification: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send password reset email.', 'error' => $e->getMessage()], 500);
        }
    }
}
