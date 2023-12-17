<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailValidationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class EmailValidationController extends Controller
{
    public function sendValidationEmail(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        $user = $request->user();

        if ($user->email !== $validatedData['email']) {
            return response()->json(['message' => 'Invalid email address.'], 422);
        }

        $user = $request->user();

        // Create a new token for email validation
        $tokenResult = $user->createToken('email-validation', ['email-validate']);

        $tokenResult->accessToken->expires_at = now()->addHours(2);
        $tokenResult->accessToken->save();

        $token = $tokenResult->plainTextToken;

        Mail::to($user->email)->send(new EmailValidationMail($user, $token));

        return response()->json(['message' => 'Validation email sent.']);
    }

    public function validateEmail(Request $request)
    {
        $token = $request->input('token');
        $tokenId = explode('|', $token, 2)[0] ?? '';

        // Find the token in the database
        $personalAccessToken = PersonalAccessToken::find($tokenId);

        if ($personalAccessToken && now()->isBefore($personalAccessToken->expires_at)) {
            $user = $personalAccessToken->tokenable;

            // The token is valid
            $user->email_verified_at = now();
            $user->save();

            // Delete the token after use
            $personalAccessToken->delete();

            return response()->json(['message' => 'Email validated successfully.']);
        }

        return response()->json(['message' => 'Invalid or expired token.'], 422);
    }
}
