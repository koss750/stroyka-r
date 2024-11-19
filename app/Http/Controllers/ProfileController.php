<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\Region;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => User::find(auth()->user()->id),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function settings()
    {
        $user = auth()->user()->load('supplier');
        $regions = Region::all();
        $supplierTypes = ['contractor' => 'Подрядчик', 'supplier' => 'Поставщик'];
        $organizationTypes = ['ИП' => 'ИП', 'ООО' => 'ООО', 'АО' => 'АО']; // Add more as needed

        return view('edit_profile', compact('user', 'regions', 'supplierTypes', 'organizationTypes'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'region_id' => 'required',
            'email_notifications' => 'nullable',
            'sms_notifications' => 'nullable',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->regions = $request->region_id;
        $user->email_notifications = $request->has('email_notifications') ? 1 : 0;
        $user->sms_notifications = $request->has('sms_notifications') ? 1 : 0;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.settings')->with('success', 'Ваши данные успешно обновлены');
    }
}
