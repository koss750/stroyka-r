<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\FeedbackMail;
use App\Models\User;
class FeedbackController extends Controller
{
    public function send(Request $request)
    {
        //check captcha
        if ($request->captcha !== '3') {
            return response()->json(['error' => 'Unauthorized'], 418);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $user = User::find(7);

        $user->notify(new FeedbackMail(
            $request->name,
            $request->phone ?? '',
            $request->email ?? '',
            $request->message
        ));

        return back()->with('success', 'Ваш запрос был отправлен!');
    }
}