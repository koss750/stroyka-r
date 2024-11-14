<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Image;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RuTranslationController as Translator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\InvoiceType;
use App\Models\ProjectPrice;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\DesignSeo;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use App\Models\Region;
use App\Models\Foundation;
use Illuminate\Support\Facades\Cache;
use App\Models\PricePlan;



class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    
    public function exportToCsv($headers, $data)
    {
        // Define the CSV filename
        $fileName = 'test.csv';

        // Define the directory where you want to save the CSV file
        $filePath = public_path($fileName);

        // Open the file for writing
        $file = fopen($filePath, 'w');

        // Write the CSV header
        fputcsv($file, $headers["formatted"]);

        // Write each data row as a CSV row
        foreach ($data as $rowData) {
            // Write the row to the CSV
            fputcsv($file, $rowData);
        }

        // Close the file
        fclose($file);
        
        $response['path'] = $filePath;
        $response['fileName'] = $fileName;

        // Generate a response to download the file
        return $response;
    }
    
    public function getHeaders() {
        $designs = Design::first();

        // Get the original column names
        $originalHeader = [];
    
        foreach ($designs->first()->getAttributes() as $column => $value) {
            $exceptions = ['category'];
            if (!in_array($column, $exceptions)) {
                $originalHeader[] = $column;
            }
        }
        
    
        // Translate and add the headers
        $headerRow = [];
        foreach ($originalHeader as $column) {
            $headerRow[] = Translator::translate($column); // Replace with your translation logic
            }
        return $headerRow;
    }
    
    public function exportAll(){
        
    
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body>';
    // Define the JSON fields
    $jsonFields = ['category', 'floorsList', 'metaList', 'stropList', 'skatList', 'endovList'];

    // Retrieve all designs from the database
    $designs = Design::all();
    
    // Convert the data to an HTML table
    $html .= '<table class="table table-striped table-bordered">';
$html .= '<thead class="thead-dark">';

    // Get the original column names
    $originalHeader = [];
    foreach ($designs->first()->getAttributes() as $column => $value) {
        $originalHeader[] = $column;
    }

    // Translate and add the headers
    $headerRow = [];
    foreach ($originalHeader as $column) {
        $headerRow[] = Translator::translate($column); // Replace with your translation logic
    }
    $html .= '<th>' . implode('</th><th>', $headerRow) . '</th>';
    $html .= '</tr></thead>';
    $html .= '<tbody>';
    
    $formattedRows = [];
    $translatedHeadersArray = $originalHeader;

    foreach ($designs as $design) {
        // Initialize a row for this design
        $rowData = [];
        $headers["original"] = [];
        $headers["formatted"] = [];
        // Add data for each column
        foreach ($originalHeader as $column) {
            $headers["original"][] = $column;
            $headers["formatted"][] = Translator::translate($column);
            $formattedRows[$design->id][$column] = $design->$column;
            // If it's a JSON field, decode it and format it
            if (in_array($column, $jsonFields)) {
                $jsonValue = json_decode(json_encode($design->$column), true);
                if (is_array($jsonValue)) {
                    $formattedValue = [];
                    foreach ($jsonValue as $floorData) {
                        $formattedFloor = [];
                        foreach ($floorData as $propKey => $propVal) {
                            $formattedFloor[] = Translator::translate($propKey) . ': ' . $propVal;
                        }
                        $formattedValue[] = '--- ' . implode(', ', $formattedFloor);
                    }
                    $formattedRows[$design->id][$column] = implode('<br>', $formattedValue);
                } else {
                    $formattedRows[$design->id][$column] = '';
                }
            } else {
                if ($column === 'category') {
                $formattedRows[$design->id][$column] = Translator::translate($design->category);
                }
                else $formattedRows[$design->id][$column] = $design->$column;
            }
            //$formattedRows[] = $rowData;
        }

        // Add the row to the HTML table
        $html .= '<tr>';
        $html .= '<td>' . implode('</td><td>', $rowData) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';

    // Output the HTML table
    //echo $html;
    
    //dd($originalHeader);
    
    
    echo '</body>
</html>';
    
    $file = $this->exportToCsv($headers, $formattedRows);
    return Response::download($file['path'], $file['fileName'], ['Content-Type: text/csv']);

    }


    /**
     * Show the form for creating a new resource.*/
    
    public function create(Request $request)
    {
        $title = $request->input('title');
        $pvParts = [
            $request->input('pvPart1'),
            $request->input('pvPart2'),
            $request->input('pvPart3'),
            $request->input('pvPart4'),
            $request->input('pvPart5'),
            $request->input('pvPart6'),
            $request->input('pvPart7'),
            $request->input('pvPart8'),
            $request->input('pvPart9'),
            $request->input('pvPart10'),
            $request->input('pvPart11'),
            $request->input('pvPart12')
            ];
        $serializePv = json_encode($pvParts);
        $mvParts = [
            $request->input('mvPart1'),
            $request->input('mvPart2'),
            $request->input('mvPart3'),
            $request->input('mvPart4'),
            $request->input('mvPart5'),
            $request->input('mvPart6'),
            $request->input('mvPart7'),
            $request->input('mvPart8'),
            $request->input('mvPart9'),
            $request->input('mvPart10'),
            $request->input('mvPart11'),
            $request->input('mvPart12')
            ];
        $meta = [
            $request->input('MetaTitle'),
            $request->input('MetaKeywords'),
            $request->input('MetaDesc'),
            $request->input('MetaHeader')
        ];
        $serializeMv = json_encode($mvParts);
        $serializeMeta = json_encode($meta);
        
        // Create a new Design instance
        $design = new Design();
        //$design->title = $title;
        $design->pvPart1 = $serializePv;
        $design->mvPart1 = $serializeMv;
        $design->Meta = $serializeMeta;
        $design->details = '{"defaultRef":471,"defaultParent":211,"price":999}';
        // Save the design to the database
        $design->save();
        
        // Upload and save the associated imagesy
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
    
                $imageModel = new Image();
                $imageModel->filename = $path;
                // Set any additional image properties...
                
                $design->images()->save($imageModel);
            }
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $designs = Design::where(function($q) use ($query) {
            $q->where('size', 'like', "{$query}%");
        })
        ->where('active', 1)
        ->whereRaw("title NOT LIKE ?", ['%.'. $query .'%'])
        ->whereRaw("title NOT LIKE ?", ['%,'. $query .'%'])
        ->select('id', 'title', 'slug')
        ->orderByRaw("size ASC")
        ->limit(10)
        ->get();

        return response()->json($designs);
    }
         
 public function store(Request $request)
    {
        /*Validate and process the request data*/
        $validatedData = $request->validate([
            'category' => 'required|string',
            'size' => 'required|string',
            'length' => 'required|string',
            'width' => 'required|string',
            'code' => 'required|string',
            'numOrders' => 'required|integer',
            // ... Add validation rules for other properties ...
        ]);
        // Create a new design instance
        $design = Design::create($validatedData);

        // Redirect or return a response as needed
        return redirect()->back()->with('success', 'Design saved successfully!');
    }

    public function updateOrder(Request $request, $id)
    {
        
        $design = Design::findOrFail($id);
        $design->order = $request->input('order');
        $design->save();

        return response()->json(['message' => 'Order updated successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Design $design)
    {
        // Retrieve the design by its ID
        $design = transformDesign(Design::find($id));

        // Check if the design exists
        if (!$design) {
            // Handle the case where the design doesn't exist
            // For example, redirect to a 404 page or show an error message
            abort(404, 'Design not found');
        }

        // Return the view with the design data
        // Assuming you have a view named 'design.show' for displaying the design
        return $design;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Design $design)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Design $design)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Design $design)
    {
        //
    }
    
    public function getDemoDesigns($category_group = 'doma_iz_brevna')
    {
        // Cache key based on the category group
        $cacheKey = "demo_designs_{$category_group}";

        $oldCategoryGroups = ['df_cat_4', 'df_cat_19', 'df_cat_20', 'df_cat_1', 'df_cat_9', 'df_cat_21', 'df_cat_22', 'df_cat_7', 'df_cat_13', 'df_cat_14', 'df_cat_2', 'df_cat_5', 'df_cat_6', 'df_cat_15', 'df_cat_16'];
        if (in_array($category_group, $oldCategoryGroups)) {
            return abort(404);
        }
        // Try to get the data from cache, if not found, compute and cache it
        $designs = Cache::remember($cacheKey, now()->addHours(12), function () use ($category_group) {
            $category_groups = [
                'doma_iz_brevna' => ['df_cat_4', 'df_cat_19', 'df_cat_20'],
                'doma_iz_brusa' => ['df_cat_1', 'df_cat_9', 'df_cat_21', 'df_cat_22'],
                'bani_iz_brevna' => ['df_cat_7', 'df_cat_13', 'df_cat_14'],
                'bani_iz_brusa' => ['df_cat_2', 'df_cat_5', 'df_cat_6', 'df_cat_15', 'df_cat_16'],
                'problematic' => ['df_cat_10', 'df_cat_3', 'df_cat_8']
            ];
    
            $designs = collect();

            foreach ($category_groups[$category_group] as $category) {
                if (in_array($category, $category_groups['problematic'])) {
                    Log::info('Attempt to access problematic category', ['category' => $category, 'ip' => request()->ip(), 'useragent' => request()->userAgent()]);
                    return abort(400);
                }
                $categoryDesigns = Design::where('category', 'LIKE', '%"category":"' . $category . '"%')
                        ->where('active', 1)
                    ->get()
                    ->sortBy('size')
                    ->map(function ($design) {
                        $design->rating = 5;
                        $design->reviewCount = 10;
                        return $this->transformDesign($design);
                    });
    
                $designs = $designs->concat($categoryDesigns);
            }
    
            return $designs->flatten();
        });

        switch ($category_group) {
            case 'doma_iz_brevna':
                $page_title = "Дома из бревна";
                break;
            case 'doma_iz_brusa':
                $page_title = "Дома из бруса";
                break;
            case 'bani_iz_brevna':
                $page_title = "Бани из бревна";
                break;
            case 'bani_iz_brusa':
                $page_title = "Бани из бруса";
                break;
        }
    
        $page_description = "Каталог проектов домов и бань. Расчет стоимости строительства и фундамента - " . $page_title;
    
        return view('alternative_index', compact('page_title', 'page_description', 'designs'));
    }

    public function getDemoDetail($id, Request $request)
{
    if (is_numeric($id)) {
        return abort(404);
    }
    $design = Design::where('slug', $id)->firstOrFail();
    $currentSetting = Setting::where('key', 'display_prices')->first()->value;
    $toolTipLabel = Setting::where('key', 'tooltip_label_'.$currentSetting)->first()->value;
    // Increment view count in Redis
    $redisKey = "design_views:{$design->id}";
    Redis::incr($redisKey);
    $design->rating = 5;
    $design->etiketka = json_decode($design->details, true)["price"];
    $design->etiketka = round($design->etiketka, 2);
    $design->etiketka = number_format($design->etiketka, 2, '.', ' ');
    $design->reviewCount = 10;
    $design = $this->transformDesign($design);
    $data = InvoiceType::all();

    $user = Auth::user();
    if($user){
        $design->user = $user;
    } else {
        $design->user = false;
    }

    $meta = DesignSeo::where('design_id', $design->id)->first();

    // Cache key for nested data
    $cacheKey = "nested_data_{$id}";

    // Try to get nested data from cache
    $nestedData = Cache::remember($cacheKey, now()->addHours(24), function () use ($data, $design) {
        return [
            'foundations' => $this->buildNestedOptions($data->where('parent', 102), $data, $design->id),
            'dd_options' => $this->buildNestedOptions($data->where('parent', 103), $data, $design->id),
            'roofs' => $this->buildNestedOptions($data->where('parent', 101), $data, $design->id),
        ];
    });

    $fullUrl = $request->fullUrl();
    $page_title = Translator::translate("listing_page_title");
    if (strpos($design->title, 'Д-ОЦБ') !== false) {
        $page_title = "Дом из бревна " . $design->title . " " . $design->length . "м x " . $design->width . "м";
    } elseif (strpos($design->title, 'Д-ПБ') !== false) {
        $page_title = "Дом из бруса " . $design->title . " " . $design->length . "м x " . $design->width . "м";
    } elseif (strpos($design->title, 'Б-ОЦБ') !== false) {
        $page_title = "Баня из бревна " . $design->title . " " . $design->length . "м x " . $design->width . "м";
    } elseif (strpos($design->title, 'Б-ПБ') !== false) {
        $page_title = "Баня из бруса " . $design->title . " " . $design->length . "м x " . $design->width . "м";
    }
    $page_description = "Расчет стоимости строительства и фундамента. " . $page_title . " " . $design->size . "м2";
    $jpgImageUrls = $design->watermarkImageUrls();
    $thumbImageUrls = $design->thumbImageUrls();
    $turboPageMeta = [
        'turbo:content' => route('design.show', $design->slug),
        'turbo:source' => route('turbo-pages.rss'),
    ];
    return view('alternative_detail', compact('page_title', 'page_description', 'design', 'nestedData', 'user', 'jpgImageUrls', 'thumbImageUrls', 'toolTipLabel', 'turboPageMeta'));
}

private function getPriceFromDb($id, $invoice_type_id)
{
    try {
        $setting = Setting::where('key', 'display_prices')->first();
        $displayPrices = $setting->value;
        $invoice_type_id = InvoiceType::where('label', $invoice_type_id)->first()->id;
        $price = ProjectPrice::where('design_id', $id)->where('invoice_type_id', $invoice_type_id)->first();
        try {
            $price = json_decode($price->price, true);
            return $price[$displayPrices];
        } catch (\Exception $e) {
            Log::error("Unable to find the price for design $id and invoice type $invoice_type_id");
            return 999;
        }
    } catch (\Exception $e) {
        Log::error("Unable to find the price for design $id and invoice type $invoice_type_id");
        throw $e;
    }
}

    private function buildNestedOptions($options, $allData, $id, $seasonal = false)
    {
        $options = $options->filter(function ($option) use ($seasonal) {
            return $option->site_sub_label !== 'FALSE' && ($option->seasonal != 1);
        })->sortBy('unique_order');

        foreach ($options as $option) {
            if ($option->site_level4_label === 'FALSE') {
                $suboptions = $allData->where('parent', $option->ref);
                $option->suboptions = $this->buildNestedOptions($suboptions, $allData, $id, $seasonal);
                foreach ($option->suboptions as $suboption) {
                    $suboption->parent_ref = $option->ref;
                }
            } else {
                /*
                //get price from Redis
                $redisKey = "stroyka_$id" . "_" . $option->label;
                $prices = json_decode(Redis::get($redisKey), true);
                try {
                    $option->data_price = $prices["material"];
                } catch (\Exception $e) {
                    $option->data_price = $this->getPriceFromDb($id, $option->label);
                }
                */
                $option->data_price = $this->getPriceFromDb($id, $option->label);
                $option->suboptions = collect($option->suboptions)
                    ->keyBy('unique_btn_group')
                    ->sortBy('unique_order');
            }
        }

        return $options;
    }

    
    public function getDemoOrder(Request $request, $payment_status = null, $new_order = null)
    {
        $user = Auth::user();
        $projects = Project::where(function($query) use ($user) {
                        $query->where('user_id', $user ? $user->id : null);
                    })
                    ->with(['design', 'executor'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($project) {
                        return $this->transformProject($project);
                    });

        $regions = Region::all();
        $userRegion = $user ? Region::find($user->region_id) : null;

        $page_title = "Заказы";
        $page_description = Translator::translate("listing_page_description");
        return view('orders', compact('page_title', 'page_description', 'projects', 'regions', 'payment_status', 'new_order'));
    }

    public function getDemoOrderNew(Request $request, $payment_status, $new_order = null)
    {
        $new_order = $new_order ?? "О5-У582У";
        return $this->getDemoOrder($request, $payment_status, $new_order);
    }
    
    private function transformProject($project)
    {
        $filepath = "http://tmp.стройка.com/storage";
        $original = $project->filepath;

        if ($project->design_id) {
            $design = Design::find($project->design_id);
            $title = $design ? $design->title : 'Unknown Design';
            if (strpos($title, 'Б-ПБ') !== false) {
                $title = 'Баня из бруса ' . $design->size . 'м2 (Проект ' . $title . ')';
            } elseif (strpos($title, 'Б-ОЦБ') !== false) {
                $title = 'Баня из бревна ' . $design->size . 'м2 (Проект ' . $title . ')';
            } elseif (strpos($title, 'Д-ОЦБ') !== false) {
                $title = 'Дом из бревна ' . $design->size . 'м2 (Проект ' . $title . ')';
            } elseif (strpos($title, 'Д-ПБ') !== false) {
                $title = 'Дом из бруса ' . $design->size . 'м2 (Проект ' . $title . ')';
            }
            $thumbnail = $design ? $design->thumbnail : null;
        } elseif ($project->foundation_id) {
            $foundation = Foundation::find($project->foundation_id);
            $title = "Расчет фундамента";
            if ($project->is_example) {
                $title = "Пример расчета фундамента";
            }
            $thumbnail = null; // Foundations might not have thumbnails
        } else {
            $title = 'Unknown Project';
            $thumbnail = null;
        }

        $configuration_string = "";
        if ($project->configuration_descriptions) {
            $configuration = $project->configuration_descriptions;
            $configurationArray = [
                $configuration['foundation'] ?? null,
                $configuration['dd'] ?? null,
                $configuration['roof'] ?? null
            ];
            $configuration_string = implode(" ", array_filter($configurationArray));
        } elseif ($project->selected_configuration) {
            // Handle foundation configuration
            $configuration_string = $foundation->site_title; // You might want to elaborate on this
        }

        $price_type = $project->price_type;
        $price_type_string = "";
        if ($price_type == 'material') {
            $price_type_string = "Только материалы";
        } elseif ($price_type == 'total') {
            $price_type_string = "Материалы + работы";
        } else $price_type_string = "";

        $configuration_string = $configuration_string . " (" . $price_type_string . ")";
        
        return [
            'id' => $project->human_ref,
            'order_id' => $project->id,
            'title' => $title,
            'status' => $project->status,
            'created_at' => $project->getFormattedCreatedAtAttribute(),
            'updated_at' => $project->getFormattedUpdatedAtAttribute(),
            'thumbnail' => $thumbnail,
            'configuration' => $configuration_string,
            'payment_amount' => $project->payment_amount,
            'payment_status' => $project->payment_status,
            'payment_reference' => $project->payment_reference,
            'payment_link' => $project->payment_link,
            'filepath' => $project->filepath,
            'is_example' => $project->is_example,
            'design' => $project->design ? $this->transformDesign($project->design) : null,
            'foundation' => $project->foundation ? $this->transformFoundation($project->foundation) : null,
            'executor' => $project->executor ? [
                'id' => $project->executor->id,
                'name' => $project->executor->name,
                'company_name' => $project->executor->supplier->company_name ?? null,
            ] : null,
        ];
    }

    private function calculateServicePrice($design)
    {
        $service_prices = [];
        
        // Get only price plans for Design entity
        $pricePlans = PricePlan::when($design instanceof Design, function($query) {
            return $query->where('dependent_entity', 'Design');
        })->get();
        
        foreach ($pricePlans as $plan) {
            $price = 0;
            
            if ($plan->dependent_type === 'range' && $plan->dependent_parameter === 'size') {
                $size = $design->size;
                $ranges = $plan->parameter_option;
                
                foreach ($ranges as $range) {
                    if ($size >= $range['from'] && $size <= $range['to']) {
                        $price = $range['price'];
                        break;
                    }
                }
            }
            
            $service_prices[$plan->code] = $price;
        }
        $service_prices['example_document'] = 0;
        return $service_prices;
    }

    // Add this new method to handle foundation transformations
    private function transformFoundation($foundation)
    {
        // Transform foundation data as needed
        return [
            'id' => $foundation->id,
            'title' => $foundation->title,
            // Add other relevant foundation properties
        ];
    }
    
    public function getDemoCheckout($id=220)
    {
        $designs = Design::where('id', $id)->take(1)
                         ->get()
                         ->map(function ($design) {
                             $design->rating = 5;
                             $design->reviewCount = 10;
                             return $this->transformDesign($design);
                         });
        $page_title = "Корзина";
        $page_description = Translator::translate("listing_page_description");
        return view('vora.ecom.checkout', compact('page_title', 'page_description', 'designs'));
    }
    
     
    
    
    
    public function getList(Request $request)
    {
        $query = Design::query();

        // Handling 'size' filter separately
        if ($request->has('size')) {
            $query->where('size', '>', $request->input('size'));
        }

        // Handling other filters based on column names
        $filters = ['df_cat', 'floors', 'baseType', 'id', 'title'];
        foreach ($filters as $filter) {
            if ($request->has($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        $designs = $query
                        ->get()
                        ->map(function ($design) {
                             return $this->transformDesign($design);
                         });

        return response()->json($designs);
    }
    
    
    
    
    public function countRooms($design)
    {
        // Check if floorsList is already an array
        if (is_array($design->floorsList)) {
            // Count only the first level elements
            return count(array_filter($design->floorsList, 'is_array'));
        }
    
        // Check if floorsList is a JSON string and decode it
        if (is_string($design->floorsList)) {
            $floorsList = json_decode($design->floorsList, true);
            if (is_array($floorsList)) {
                return count(array_filter($design->floorsList, 'is_array'));
            }
        }
    }

    public function getById($id) {
        return Design::where('id', $id)->first();
    }

    private function transformDesign($design, $lang = 'ru-ru')
    {
        if ($lang === 'ru-ru') {
            $design->main_category = Translator::translate($design->category[0]["category"]);
            $design->rooms = Translator::translate($design->rooms);
        }
        
        //$design->image_url = "storage/1/conversions/WhatsApp-Image-2023-12-15-at-15.09.55-(2)-mild.jpg";
        
        $design->rooms = $this->countRooms($design);
        
        $design->setImages();
        $design->setPrice($design);
        $design->setMaterialDescription();
        $design->setDefaultRef();
        $design->service_prices = $this->calculateServicePrice($design);
        $design->image_url = $design->mildMailImage();
        // Add smetaPrice calculation
        $design->smeta_price = $this->calculateSmetaPrice($design->size);

        return $design;
    }

    private function calculateSmetaPrice($size, $category = 'smeta-normal')
    {
        $customerPrices = Setting::where('key', 'customer_prices');
        /*
        foreach ($customerPrices as $price) {
            if ($size >= $price->range_from && $size <= $price->range_to) {
                return $price->value;
            }
        }
        */
        return 200;
    }

    
    

}
