<?php

namespace App\Http\Controllers;

use App\Mail\CustomEmail; // Import the Mailable class
use App\Models\Otp;
use App\Models\User;
use App\Models\Verificationotp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        if ($request->input('to') == 0) {
            $request->validate([
                'subject' => 'required|string',
                'body' => 'required|string',
            ]);
        } else {
            $request->validate([
                'to' => 'required|email',
                'subject' => 'required|string',
                'body' => 'required|string',
            ]);
        }

        // Get the sender address from the .env file
        $fromAddress = env('MAIL_FROM_ADDRESS');
        try {
            // Determine the recipient address
            $recipientAddress = $request->input('to') == 0 ? $fromAddress : $request->input('to');

            // Send the email using the determined recipient address
            Mail::to($recipientAddress)->send(new CustomEmail(
                $fromAddress, // Use the sender address from the .env
                $request->input('subject'),
                $request->input('body')
            ));

            return response()->json(['message' => 'Email sent successfully!'], 200);
        } catch (Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send email.', 'error' => $e->getMessage()], 500);
        }
    }
    public function sendVerificationCode(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Check if the email already exists in the users table
        if (User::where('email', $email)->exists()) {
            return response()->json(['error' => 'Email already exists.'], 200);
        }

        $verificationCode = random_int(100000, 999999); // Generate a random 6-int code

        // Define the validity period (e.g., 1 minute)
        $validityPeriod = now()->addMinutes(1);


        try {
            // Store the verification code in the Otp table
            Verificationotp::create([
                'identifier' => $email,
                'token' => $verificationCode,
                'validity' => $validityPeriod,
                'valid' => true,
            ]);
            // Get the sender address from the .env file
            $fromAddress = env('MAIL_FROM_ADDRESS');

            // Send the verification code to the user's email
            Mail::to($email)->send(new CustomEmail(
                $fromAddress,
                'Email Verification Code',
                "Your verification code is: $verificationCode, Please use this code verify your account. For your security, do not share this code with anyone. If you did not request this code, please ignore this message. Thank you"
            ));

            return response()->json(['message' => 'Verification code sent to your email.'], 200);
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send verification code.', 'error' => 'An error occurred while sending the email. Please try again later.'], 500);
        }
    }
}
