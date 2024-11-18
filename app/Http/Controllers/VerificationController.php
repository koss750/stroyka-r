<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ConfirmEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    public function sendInitialVerificationEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $user->notify(new ConfirmEmail($user->id));

        return response()->json(['message' => 'Verification email sent'], 200);
    }

    public function sendVerificationEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $user->notify(new ConfirmEmail($user->id));

        return response()->json(['message' => 'Verification email sent'], 200);
    }

    public function verifyEmail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (! URL::hasValidSignature($request)) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            redirect()->route('login', ['marker' => 'email_verified']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('login', ['marker' => 'email_verified']);
    }
}