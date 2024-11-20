<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use App\Models\Supplier;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/site';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:11'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function create(Request $request, $bypass_verification = false)
    {
        if (env('ALLOW_NEW_USER_REGISTRATION') === false) {
            return abort(403, 'New user registration is not allowed');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Send verification email
        $verificationController = new VerificationController();
        $verificationController->sendInitialVerificationEmail($user);

        if ($bypass_verification) {
            auth()->login($user);
        }
        return $user;
    }

    public function newRegistrationFullForm(Request $request)
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

    public function confirmLegalEntity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255'],
            'inn' => ['required', 'string', 'size:10', 'unique:suppliers'],
            'ogrn' => ['required', 'string', 'size:13', 'unique:suppliers'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Store the company data in the session
        $request->session()->put('legal_entity_data', $request->all());

        // Return a success response with a redirect URL
        return response()->json([
            'success' => true,
            'redirect' => route('legal-entity.complete-registration')
        ]);
    }

    public function completeRegistration(Request $request)
    {
        // Retrieve the company data from the session
        $companyData = $request->session()->get('legal_entity_data');

        // Render the view with the company data
        //return view('legal_entity_confirmation');
    }

    public function registerLegalEntity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'inn' => ['required', 'string', 'size:10', 'unique:suppliers'],
            'ogrn' => ['required', 'string', 'size:13', 'unique:suppliers'],
            'legal_address' => ['required', 'string', 'max:255'],
            'physical_address' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Create user
        $user = User::create([
            'name' => $request->contact_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'legal_entity',
        ]);

        // Create supplier
        $supplier = $user->supplier()->create([
            'company_name' => $request->company_name,
            'inn' => $request->inn,
            'email' => $request->email,
            'phone' => $request->phone,
            'additional_phone' => $request->additional_phone,
            'contact_name' => $request->contact_name,
            'ogrn' => $request->ogrn,
            'legal_address' => $request->legal_address,
            'physical_address' => $request->physical_address,
        ]);

        // Clear the session data
        $request->session()->forget('legal_entity_data');

        return response()->json([
            'success' => true,
            'message' => 'Регистрация успешно завершена',
            'redirect' => route('home') // or wherever you want to redirect after successful registration
        ]);
    }
}
