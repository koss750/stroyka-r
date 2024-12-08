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
use Illuminate\Support\Facades\Validator;

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
        $organizationTypes = ['st' => 'ИП', 'ltd' => 'ООО']; // Add more as needed

        return view('edit_profile', compact('user', 'regions', 'supplierTypes', 'organizationTypes'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'region_id' => 'nullable|integer',
            'email_notifications' => 'nullable',
            'sms_notifications' => 'nullable',
            'password' => 'nullable|string|min:8',
            'supplier.company_name' => 'nullable|string|max:255',
            'supplier.phone' => 'nullable|string|max:255',
            'supplier.additional_phone' => 'nullable|string|max:255',
            'supplier.yandex_maps_link' => 'nullable|string|max:550',
            'supplier.type_of_organisation' => 'nullable|string|max:255',
            'supplier.message' => 'nullable|string',
            'supplier.regions' => 'nullable|string', // Assuming regions are passed as a comma-separated string
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->regions = $request->region_id;
        $user->phone = $request->phone ?? ($request->input('supplier.phone') ?? null);
        if (is_null($user->regions)) {
            if($request->region_id) {
                $user->regions = $request->region_id;
            } else {
                $user->regions = 1;
            }
        }
        $user->email_notifications = $request->has('email_notifications') ? 1 : 0;
        $user->sms_notifications = $request->has('sms_notifications') ? 1 : 0;

        if ($request->has('password') && $request->password != '') {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Handle supplier data
        if ($request->filled('supplier')) {
            $supplier = $user->supplier;
            $supplier->phone = $request->input('supplier.phone');
            if (is_null($supplier->yandex_maps_link) && $request->input('supplier.yandex_maps_link')) {
                $supplier->yandex_maps_link = $request->input('supplier.yandex_maps_link');
            }
            $supplier->additional_phone = $request->input('supplier.additional_phone');
            $supplier->type_of_organisation = $request->input('supplier.type_of_organisation');
            $supplier->message = $request->input('supplier.message');

            // Update supplier regions
            if ($request->filled('supplier.regions')) {
                $regionIds = explode(',', $request->input('supplier.regions'));
                $supplier->regions()->sync($regionIds);
            }

            $supplier->save();
        }

        return redirect()->route('user.settings')->with('success', 'Ваши данные успешно обновлены');
    }

    public function profileLegalEntityConfirmation(Request $request)
    {
        // Update the validation rules
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255'],
            'inn' => ['required', 'string', 'size:10', 'unique:suppliers'], 
            'ogrn' => ['required', 'string', 'size:13', 'unique:suppliers'], 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        return view('legal_entity_confirmation', [
            'company_name' => $request->company_name,
            'inn' => $request->inn,
            'kpp' => $request->kpp,
            'ogrn' => $request->ogrn,
            'legal_address' => $request->address,
            'physical_address' => $request->physical_address ?? '',
            'email' => $request->email ?? '',
            'phone' => $request->phone ?? '',
            'additional_phone' => $request->additional_phone ?? '',
            'contact_name' => $request->contact_name ?? '',
            'selected_regions' => $selectedRegions ?? [], // Assuming you have this data
        ]);
    }
}
