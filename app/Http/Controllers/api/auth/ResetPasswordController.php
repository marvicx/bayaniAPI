<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\ResetPasswordRequest;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $otp2 = $this->otp->validate($request->email, $request->otp);
        if (! $otp2->status) {
            return response()->json(['error' => $otp2], 401);
        }
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}
