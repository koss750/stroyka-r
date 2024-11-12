<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Project;
use App\Models\Region;


class SupplierController extends Controller
{
    public function registerCompany(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'inn' => 'required|string',
            'company_name' => 'required|string',
            'kpp' => 'required|string',
            'ogrn' => 'required|string',
            'legal_address' => 'required|string',
            'phone' => 'required|string',
            'physical_address' => 'required|string',
            'state_status' => 'required|string',
            'company_age' => 'required|string',
            'email' => 'required|email|unique:users',
            'additional_phone' => 'required|string',
            'contact_name' => 'required|string',
            'password' => 'required|string|min:8',
            'region_codes' => 'required|json'
        ]);
    
        // Create user
        $user = new User();
        $user->name = $validatedData['contact_name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();
    
        // Create supplier
        $supplier = new Supplier();
        $supplier->user_id = $user->id;
        $supplier->type = 'legal_entity';
        $supplier->company_name = $validatedData['company_name'];
        $supplier->inn = $validatedData['inn'];
        $supplier->kpp = $validatedData['kpp'];
        $supplier->ogrn = $validatedData['ogrn'];
        $supplier->legal_address = $validatedData['legal_address'];
        $supplier->physical_address = $validatedData['physical_address'];
        $supplier->phone = $validatedData['phone'];
        $supplier->additional_phone = $validatedData['additional_phone'];
        $supplier->email = $validatedData['email'];
        $supplier->contact_name = $validatedData['contact_name'];
        $supplier->state_status = $validatedData['state_status'];
        $supplier->company_age = $validatedData['company_age'];
        $supplier->status = 'pending';
        $supplier->region_codes = $validatedData['region_codes'];
        $supplier->save();
    
        Auth::login($user);
    
        // Send email to admins about new pending registration
        // Implement this part based on your email setup
    
        return response()->json(['success' => true, 'message' => 'Регистрация успешно отправлена']);
    }

    // Список поставщиков
    public function indexSuppliers(Request $request)
    {
        $page_title = 'Трудяги';
        $page_description = 'Наши партнёры';

        $query = Supplier::where('status', 'approved')->with('regions');

        if ($request->has('type')) {
            switch ($request->type) {
                case 'ltd':
                    $query->where('type_of_organisation', 'ltd');
                    break;
                case 'brigade':
                    $query->where('type_of_organisation', 'brigade');
                    break;
                case 'se':
                    $query->where('type_of_organisation', 'se');
                    break;
            }
        }

        $suppliers = $query->get();

        return view('suppliers-index', compact('page_title', 'page_description', 'suppliers'));
    }

    // Профиль поставщика
    public function viewProfile($id)
    {
        $supplier = Supplier::with('regions')->findOrFail($id);
        $currentUser = auth()->user();
        return view('view-profile', compact('supplier', 'currentUser'));
    }

    // Проверка компании по ИНН
    public function checkCompany($inn)
    {
        $token = env('DADATA_API');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => "Token $token",
        ])->post('http://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party', [
            'query' => $inn
        ]);

        $data = $response->json();

        if (isset($data['suggestions'][0])) {
            $suggestion = $data['suggestions'][0];
            if ($suggestion['data']['state']['status'] == 'ACTIVE') {
                $is_active = true;
            } else {
                $is_active = false;
            }
            return response()->json([
                'success' => true,
                'company_name' => $suggestion['value'],
                'kpp' => $suggestion['data']['kpp'],
                'ogrn' => $suggestion['data']['ogrn'],
                'address' => $suggestion['data']['address']['value'],
                'state_status' => $suggestion['data']['state']['status'],
                'is_active' => $is_active,
                'ogrn_date' => $suggestion['data']['ogrn_date'],
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getExecutors(Request $request)
    {
        $region_id = $request->input('region_id');
        
        $executors = User::role('executor')
            ->whereHas('supplier', function ($query) {
                $query->where('status', 'active');
            })
            ->with(['companyProfile', 'supplier'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'company_name' => $user->supplier->company_name,
                    'image' => $user->companyProfile->image ?? null,
                ];
            });

        return response()->json($executors);
    }

    public function getAvailableExecutors($projectId)
    {
        $project = Project::find($projectId);
        $userId = $project->user_id;
        $userRegion = User::find($userId)->regions;
        $executors = Supplier::where('region_code', 'like', '%' . $userRegion . '%')->where('status', 'approved')->get();
        return response()->json($executors);
    }

    public function assignExecutorModal()
    {
        $user = auth()->user();
        $defaultRegion = Region::find($user->region_id);
        $regions = Region::all();

        return view('modals.assign-executor', compact('defaultRegion', 'regions'));
    }
}