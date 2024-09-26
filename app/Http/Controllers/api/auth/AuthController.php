<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Models\Verificationotp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return JsonResponse
     */
    public function register(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'user_type' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'email_verified_at' => now(),
            'user_type' => request('user_type'),
            'password' => Hash::make(request('password')),
        ]);

        return $this->login();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $token = auth()->refresh();
        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        $user = auth()->user();
        return response()->json([
            'localId' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'status' => $user->status,
            'user_type' => $user->user_type,
            'personID' => $user->personID,
            'access_token' => 'bearer' . ' ' . $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }


    public function verifyCode(Request $request)
    {
        // Validate the input code
        $request->validate([
            'verificationCode' => 'required|string',
            'email' => 'required|email', // Validate the email as well
        ]);

        $inputCode = $request->input('verificationCode'); // Code entered by the user
        $email = $request->input('email'); // Get the email from the request

        // Find the OTP record associated with the email 
        $otp = Verificationotp::where('identifier', $email)
            ->where('valid', true)
            ->where('validity', '>', now()) // Check if the code is still valid
            ->first();

        // Check if an OTP record was found and if the input code matches the token
        if ($otp && $inputCode === $otp->token) {
            // Optionally, you may want to mark the OTP as used or invalidate it
            $otp->valid = false; // Invalidate the code after successful verification
            $otp->save();

            return response()->json(['message' => 'Code verified successfully. You can now proceed to register.']);
        }

        return response()->json(['error' => 'Invalid verification code.'], 400);
    }
}
