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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

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
            'regions' => $request->main_region ?? 1
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
            'ogrn' => ['required', 'string', 'size:13'], 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $legalAddress = $request->input('address');
        $mainRegion = null;

        // Query DaData API for address validation
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Token ' . env('DADATA_API')
        ])->post('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', [
            'query' => $legalAddress
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data['suggestions'][0])) {
                $suggestion = $data['suggestions'][0];
                $regionIsoCode = $suggestion['data']['region_iso_code'] ?? null;
                
                // Find matching region in our database
                if ($regionIsoCode) {
                    $mainRegion = DB::table('regions')
                        ->where('iso_code', $regionIsoCode)
                        ->orWhere('id', $regionIsoCode)
                        ->first();
                }
            }
        }
        //dd($mainRegion, $regionIsoCode);

        return view('legal_entity_confirmation', [
            'company_name' => $request->input('company_name'),
            'inn' => $request->input('inn'),
            'kpp' => $request->input('kpp'),
            'ogrn' => $request->input('ogrn'),
            'legal_address' => $legalAddress,
            'physical_address' => $request->input('physical_address'),
            'main_region_id' => $mainRegion->id,
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'additional_phone' => $request->input('additional_phone'),
            'contact_name' => $request->input('contact_name'),
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

    public function bringLoggedInUserRegistrationForm(Request $request)
    {
        return view('auth.register', [
            'type' => 'legal'
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
        $user_id = $request->user_id;
        
        // First, let's debug what we're receiving

        $type_of_registration = "ltd";
        if (strpos($request->company_name, "ИП") !== false) {
            $type_of_registration = "st";
        }

        $validator = Validator::make($request->all(), [
            'user_id' => ['required'], // Add this validation
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:suppliers'],
            'password' => ['required', 'string', 'min:8'],
            'inn' => ['required', 'string', 'size:10', 'unique:suppliers'],
            'ogrn' => ['required', 'string', 'size:13', 'unique:suppliers'],
            'legal_address' => ['required', 'string', 'max:255'],
            'physical_address' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Create supplier with explicit values for all required fields
            $supplier = Supplier::create([
                'user_id' => $user_id,
                'company_name' => $request->company_name,
                'inn' => $request->inn,
                'email' => $request->email,
                'phone' => $request->phone,
                'additional_phone' => $request->additional_phone ?? null,
                'contact_name' => $request->contact_name,
                'ogrn' => $request->ogrn,
                'legal_address' => $request->legal_address,
                'physical_address' => $request->physical_address,   
                'yandex_maps_link' => $request->yandex_maps_link ?? null,
                'type_of_registration' => $type_of_registration,
                'address' => $request->physical_address,
                'status' => 'unpaid',
            ]);

            // Clear the session data
            $supplier->status = 'pending';
            $supplier->save();
            \Log::info('Supplier created successfully: ' . $supplier->id);
            return $supplier->id;
        } catch (\Exception $e) {
            \Log::info('Supplier created failed');
            dd($e);
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при регистрации',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
