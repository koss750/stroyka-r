<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use App\Jobs\GenerateOrderExcelJob;
use App\Models\InvoiceType;
use App\Models\ProjectPrice;
use App\Models\Design;
use App\Models\Setting;
use App\Models\Foundation;
use App\Jobs\FoundationOrderFileJob;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }

    public function createSmetaOrder(Request $request)
    {
        $designId = $request->input('design_id');
        $price_type = $request->input('price_type');
        $selectedOptions = json_decode($request->input('selected_configuration'));
        if ($selectedOptions->roof == 222) unset($selectedOptions->roof);
        if ($selectedOptions->foundation == 220) unset($selectedOptions->foundation);
        $configurationDescriptions = json_decode($request->input('configuration_descriptions'));
        $paymentAmount = $request->input('payment_amount');
        $orderType = $request->input('order_type_label') ?? ($request->input('order_type') ?? 'unknown');
        $ipAddress = $request->ip();
        $project = Project::create([
            'user_id' => $request->input('user_id'),
            'human_ref' => $this->generateHumanReference($designId, $orderType),
            'order_type' => $orderType,
            'ip_address' => $ipAddress,
            'payment_reference' => 'test',
            'payment_amount' => $paymentAmount,
            'price_type' => $price_type,
            'design_id' => $designId,
            'selected_configuration' => $selectedOptions,
            'configuration_descriptions' => $configurationDescriptions,
        ]);

        dispatch(new GenerateOrderExcelJob($project->id));

        return $project->id;
    }

    public function generateHumanReference($itemId, $orderType)
    {
        // create an alphanumeric code starting with project ID and using some base of selectedOptions
        $letters = ['А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х', '2', '4', '5', '6', '7', '8', '9'];
        $randomLetters = '';
        for ($i = 0; $i < 5; $i++) {
            $randomLetters .= $letters[array_rand($letters)];
        }
        switch ($orderType) {
            case 'smeta':
                $humanReference = "С" . $itemId . "-" . $randomLetters;
                break;
            case 's_example':
                $humanReference = "СМ-" . $randomLetters;
                break;
            case 'foundation':
                $humanReference = "О" . $itemId . "-" . $randomLetters;
                break;
            case 'fnd_ex':
                $humanReference = "OM" . $itemId . "-" . $randomLetters;
                break;
            default:
                $humanReference = "Х3" . $itemId . "-" . $randomLetters;
                break;
        }
        return $humanReference;
    }

    public function createOrder(Request $request)
    {
        $validatedData = $request->validate([
            'design_id' => 'required|exists:designs,id',
            'selected_configuration' => 'required|array',
            'payment_amount' => 'required|numeric',
            'ip_address' => 'required|ip',
        ]);

        dd('selected_configuration');

        $project = Project::create([
            'user_id' => auth()->id(),
            'design_id' => $validatedData['design_id'],
            'selected_configuration' => $validatedData['selected_configuration'],
            'payment_amount' => $validatedData['payment_amount'],
            'ip_address' => $validatedData['ip_address'],
            'payment_reference' => 'test', // You might want to generate this dynamically
        ]);

        GenerateOrderExcelJob::dispatch($project->id);

        return response()->json([
            'message' => 'Order created successfully',
            'project_id' => $project->id,
            'excel_file' => $excelFile,
        ]);
    }

    private function generateExcelFile(Project $project)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Project ID: ' . $project->id);
        $sheet->setCellValue('A2', 'Design ID: ' . $project->design_id);

        $row = 4;
        foreach ($project->selected_configuration as $key => $value) {
            $sheet->setCellValue('A' . $row, $key);
            $sheet->setCellValue('B' . $row, $value);
            $row++;
        }

        $fileName = 'project_' . $project->id . '_smeta.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return asset('storage/' . $fileName);
    }

    public function assignExecutors(Project $project, Request $request)
    {
        $validatedData = $request->validate([
            'executor_ids' => 'required|array',
            'executor_ids.*' => 'exists:users,id',
        ]);

        $executors = User::whereIn('id', $validatedData['executor_ids'])->where('role', 'executor')->get();
        $project->executors()->syncWithoutDetaching($executors);

        return response()->json(['message' => 'Executors assigned successfully']);
    }

    public function reviewProject(Project $project)
    {
        // Ensure the authenticated user is an executor assigned to this project
        if (!$project->executors->contains(auth()->id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $projectData = [
            'id' => $project->id,
            'selected_configuration' => $project->selected_configuration,
            'excel_file' => $project->filepath ? Storage::url($project->filepath) : null,
        ];

        return response()->json($projectData);
    }

    public function view($id)
    {
        $project = Project::where('human_ref', $id)
                      ->where('user_id', auth()->id())
                      ->firstOrFail();
        $designTitle = Design::where('id', $project->design_id)->firstOrFail()->title;
        $selectedConfiguration = $project->selected_configuration;
        $priceSetting = $project->price_type;
        if ($priceSetting == 'smeta_project_labour' || str_contains($priceSetting, 'foundation')) {
            $displayLabour = true;
        } else {
            $displayLabour = false;
        }
        $invoiceTypeIds = [];

        if (isset($selectedConfiguration['foundation']) && InvoiceType::where('ref', $selectedConfiguration['foundation'])->exists()) {
            $invoiceTypeIds[] = InvoiceType::where('ref', $selectedConfiguration['foundation'])->first()->id;
        }

        if (isset($selectedConfiguration['dd']) && InvoiceType::where('ref', $selectedConfiguration['dd'])->exists()) {
            $invoiceTypeIds[] = InvoiceType::where('ref', $selectedConfiguration['dd'])->first()->id;
        }

        if (isset($selectedConfiguration['roof']) && InvoiceType::where('ref', $selectedConfiguration['roof'])->exists()) {
            $invoiceTypeIds[] = InvoiceType::where('ref', $selectedConfiguration['roof'])->first()->id;
        }
        $sheetStructures = [];
        $totalLabour = 0;
        $totalMaterial = 0;
        $titleOrder = 0;
        foreach ($invoiceTypeIds as $invoiceTypeId) {
            $titleOrder++;
            $projectPrice = ProjectPrice::where('invoice_type_id', $invoiceTypeId)
                                        ->where('design_id', $project->design_id)
                                        ->firstOrFail();

            if ($projectPrice) {
                $invoiceType = InvoiceType::where('id', $invoiceTypeId)->firstOrFail();
                $data = json_decode($projectPrice->parameters, true);
                $sheetStructure = $data['sheet_structure'] ?? null;
                $smetaTitle = str_replace('{order}', $titleOrder, $invoiceType->custom_order_title);
                if ($sheetStructure) {
                    $sheetStructures[] = [
                        'title' => $smetaTitle,
                        'data' => $sheetStructure
                    ];

                    // Calculate totals
                    foreach ($sheetStructure['sections'] as $section) {
                        foreach ($section['labourItems'] as $item) {
                            $addableItem = false;
                            if (isset($item['labourTotal']) && is_numeric($item['labourTotal'])) {
                                $addableItem = true;
                            }
                            if ($addableItem) {
                                $totalLabour += $item['labourTotal'] ?? 0;
                            }
                        }
                        foreach ($section['materialItems'] as $item) {
                            $addableItem = false;
                            if (isset($item['materialTotal']) && is_numeric($item['materialTotal'])) {
                                $addableItem = true;
                            }
                            if ($addableItem) {
                                $totalMaterial += $item['materialTotal'] ?? 0;
                            }
                        }
                    }
                }
            }
        }

        if (empty($sheetStructures)) {
            return redirect()->back()->with('error', 'Order data not found.');
        }


        $combinedTotals = [
            'labour' => 0,
            'material' => 0,
            'total' => 0,
            'shipping' => 0
        ];
        foreach ($sheetStructures as $sheetStructure) {
            foreach ($sheetStructure['data']['totals'] as $key=>$total) {
                $combinedTotals[$key] += $total;
            }
        }
        $totals = $combinedTotals;

        return view('order_view', compact('project', 'sheetStructures', 'totals', 'designTitle', 'displayLabour'));
    }

    public function selectFoundationSettings(Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id',
            'cellMappings' => 'required|array',
        ]);

        $foundationId = $request->input('foundation_id');
        $cellMappings = $request->input('cellMappings');

        // Here you might want to do some processing or validation of the cell mappings
        // For now, we'll just return a success response

        return response()->json([
            'message' => 'Foundation settings received successfully',
            'foundation_id' => $foundationId,
        ]);
    }

    public function generateFoundationOrder(Request $request)
    {
        $request->validate([
            'foundation_id' => 'required|exists:foundations,id',
            'cellMappings' => 'required|array',
            'user_id' => 'required|exists:users,id',
        ]);

        $foundationId = $request->input('foundation_id');
        $cellMappings = $request->input('cellMappings');
        $userId = $request->input('user_id');

        $foundation = Foundation::findOrFail($foundationId);

        $orderType = 'foundation';

        // Create a new project for the foundation order
        $project = Project::create([
            'user_id' => $userId,
            'human_ref' => $this->generateHumanReference($foundation->id, $orderType),
            'order_type' => $orderType,
            'ip_address' => $request->ip(),
            'is_example' => $request->input('is_example'),
            'payment_reference' => 'pending',
            'payment_amount' => 200, // This will be updated later
            'foundation_id' => $foundation->id,
            'selected_configuration' => $cellMappings,
        ]);

        // Dispatch the job to generate the foundation order file
        FoundationOrderFileJob::dispatch($project);
        GenerateOrderExcelJob::dispatch($project->id);

        return response()->json([
            'message' => 'Foundation order generation job dispatched successfully',
            'project_id' => $project->id,
            'order_id' => $project->human_ref,
        ]);
    }

    public function submitOffer(Project $project, Request $request)
    {
        // Ensure the authenticated user is an executor assigned to this project
        if (!$project->executors->contains(auth()->id())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'status' => 'required|in:accepted,rejected',
            'price' => 'required_if:status,accepted|numeric|nullable',
            'comment' => 'nullable|string',
        ]);

        $project->executors()->updateExistingPivot(auth()->id(), $validatedData);

        return response()->json(['message' => 'Offer submitted successfully']);
    }

    public function getAvailableExecutors(Project $project)
    {
        $executors = User::whereHas('roles', function($query) {
            $query->where('roles', 'like', '%executor%');
        })
        ->whereDoesntHave('projects', function($query) use ($project) {
            $query->where('project_id', $project->id);
        })
        ->with('companyProfile')
        ->get(['id', 'name']);

        return response()->json(['executors' => $executors]);
    }
}