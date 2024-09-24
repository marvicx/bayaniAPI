<?php

namespace App\Http\Controllers;

use App\Mail\CustomEmail; // Import the Mailable class
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Validate the payload
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        // Get the sender address from the .env file
        $fromAddress = env('MAIL_FROM_ADDRESS');

        try {
            // Send the email using the fixed from address from .env
            Mail::to($request->input('to'))->send(new CustomEmail(
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
}
