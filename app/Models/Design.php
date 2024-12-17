<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Floor;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use HasTranslations;
use Vanilo\Product\Models\Product;
use App\Models\DesignPrice;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\File;
use App\Models\ExcelFileType as AssociatedCostModel;
use App\Models\ProjectPrice;
use Spatie\Image\Manipulations;
use signifly\Nova\Fields\ProgressBar\ProgressBar;
use App\Http\Controllers\RuTranslationController as Translator;
use App\Models\DesignSeo;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Laravel\Nova\Actions\Actionable;


class Design extends Model implements HasMedia
{
	use InteractsWithMedia;
    use Authorizable;
    use Actionable;
	 
    
	protected $table = 'designs';
	
	public $timestamps = true;

    // Fillable fields for mass assignment
    protected $fillable = [
        'details',
        'created_at',
        'updated_at',
        'category',
        'size',
        'mMetrics',
        'length',
        'width',
        'code',
        'numOrders',
        'materialType',
        'floorsList',
        'baseType',
        'roofType',
        'roofSquare',
        'mainSquare',
        'baseLength',
        'baseD20',
        'baseD20F',
        'baseD20Rub',
        'baseD20RubF',
        'baseBalk1',
        'baseBalkF',
        'baseBalk2',
        'wallsOut',
        'wallsIn',
        'wallsPerOut',
        'wallsPerIn',
        'rubRoof',
        'skatList',
        'krovlaList',
        'stropList',
        'stropValue',
        'endovList',
        'areafl0',
        'metaList',
        'indexed_prices',
        'etiketka',
        'etiketka_seasonal',
        'slug',
        // ... include other fields as needed
    ];

	protected $casts = [
	    'floorsList' => 'json',
	    'category' => 'json',
	    'skatList' => 'json',
	    'stropList' => 'json',
	    'areafl0' => 'json',
        'endovList' => 'json',
        'metaList' => 'json',
        'krovlaList' => 'json',
        'pvParts' => 'json', // or 'object' if you prefer
        'mvParts' => 'json', // or 'object' if you prefer
        'indexed_prices' => 'array'
    ];

    private $completionFields = [
        'lfLength', 'lfAngleX', 'lfAngleT', 'lfAngleG', 'lfAngle45', 'mfSquare', 'vfCount',
        'vfBalk', 'outer_p', 'baseD20RubF', 'baseBalk1', 'stolby', 'baseLength', 'baseD20', 'roofSquare', 'srCherep', 'srKover',
        'srKonK', 'srMastika1', 'srMastika', 'mrKon', 'srKonOneSkat', 'srPlanVetr',
        'srPlanK', 'srKapelnik', 'mrEndv', 'srGvozd', 'mrSam70', 'mrPack', 'mrIzospanAM',
        'mrIzospanAM35', 'mrLenta', 'mrRokvul', 'mrIzospanB', 'mrIzospanB35', 'mrPrimUgol',
        'mrPrimNakl', 'srOSB', 'srAero', 'srAeroSkat', 'stropValue',
        'pvPart1', 'pvPart2', 'pvPart3', 'pvPart4', 'pvPart5', 'pvPart6', 'pvPart7',
        'pvPart8', 'pvPart9', 'pvPart10', 'pvPart11', 'pvPart12', 'pvPart13',
        'mvPart2', 'mvPart3', 'mvPart4', 'mvPart5', 'mvPart6', 'mvPart7', 'mvPart8',
        'mvPart9', 'mvPart10', 'mvPart11', 'mvPart12', 'mvPart13',
        'srKonShir', 'srEndn', 'srEndv', 'mrSam35', 'srSam70', 'srPack', 'srIzospanAM',
        'srIzospanAM35', 'srPrimUgol', 'srPrimNakl', 'metaList', 'floorsList', 'stropList', 'areafl0'
    ];

    /**
     * Get the project prices for the design
     * 
     * @return HasMany
     */
    public function projectPrices()
    {
        return $this->hasMany(ProjectPrice::class)
            ->select(['id', 'design_id', 'invoice_type_id', 'price', 'created_at'])
            ->latest()
            ->groupBy(['invoice_type_id', 'id', 'design_id', 'price', 'created_at']);
    }

    /**
     * Update the indexed prices for the design
     * 
     * @return void
     */
    public function updateIndexedPrices()
    {
        $prices = $this->projectPrices()->pluck('price', 'invoice_type_id')->toArray();
        $this->indexed_prices = $prices;
        $this->save();
    }

    public function getEtiketkaAttribute()
    {
        $projectPrice = ProjectPrice::where('design_id', $this->id)->where('invoice_type_id', 187)->first();
        if($projectPrice) {
            $prices = json_decode($projectPrice->price, true);
            return $prices['material'];
        } else {
            return 0;
        }
    }

    public function getProgressAttribute()
    {
        $totalFields = count($this->completionFields);
        $filledFields = 0;

        foreach ($this->completionFields as $field) {
            if ($this->$field !== null) {
                $filledFields++;
            }
        }

        // Calculate progress for fields (90% of total progress)
        $fieldProgress = ($filledFields / $totalFields) * 0.9;

        // Calculate progress for images (10% of total progress)
        $imageCount = $this->getMedia('images')->count();
        $imageProgress = min($imageCount * 0.025, 0.1);  // Cap at 0.1 (4 images)

        // Total progress
        $totalProgress = $fieldProgress + $imageProgress;

        // Round to 2 decimal places
        return round($totalProgress, 2);
    }

    public function getIncompleteFieldsAttribute()
    {
        $incompleteFields = [];
        foreach ($this->completionFields as $field) {
            if ($this->$field === null) {
                $incompleteFields[] = Translator::translate($field);
            }
        }
        return $incompleteFields;
    }

    public function getEtiketkaSeasonalAttribute()
    {
        $projectPrice = ProjectPrice::where('design_id', $this->id)->where('invoice_type_id', 189)->first();
        if($projectPrice) {
            $prices = json_decode($projectPrice->price, true);
            return $prices['material'];
        } else {
            return 0;
        }
    }

	/**
	 *      * The attributes that are mass assignable.
	 *           *
	 *                * @var array
	 *                     */
	public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('jpg')
            ->format('jpg')
            ->quality(99);
        $this->addMediaConversion('milder')
            ->width(500)
            ->height(500)
            ->performOnCollections('images');
        $this->addMediaConversion('mild')
            ->width(1000)
            ->height(2000)
            ->performOnCollections('images');
        $this->addMediaConversion('thumb')
            ->width(500)
            ->height(500)
            ->performOnCollections('images');
        $this->addMediaConversion('thumbie')
            ->width(100)
            ->height(100)
            ->performOnCollections('images');
        $this->addMediaConversion('watermarked')
            ->width(1800)
            ->height(1800)
            ->performOnCollections('images')
            ->watermark(public_path('watermark.png'))
            ->watermarkPosition(Manipulations::POSITION_CENTER)
            ->watermarkHeight(100, Manipulations::UNIT_PERCENT)
            ->watermarkWidth(100, Manipulations::UNIT_PERCENT)
            ->watermarkFit(Manipulations::FIT_STRETCH)
            ->watermarkPadding(0, 0, Manipulations::UNIT_PIXELS)
            ->watermarkOpacity(23);
    }

public function registerMediaCollections(): void
{
    try {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('my_multi_collection');
    } catch (\Exception $e) {
        \Log::error('Media collection registration failed: ' . $e->getMessage());
    }
}
    public function watermarkImageUrls()
    {
        return $this->getMedia('images')->map(function ($media) {
            return url($media->getUrl('watermarked')); 
                })->all();
    }

    public function thumbImageUrls()
    {
        return $this->getMedia('images')->map(function ($media) {
                    return url($media->getUrl('thumbie')); 
                })->all();
    }

    public function mildMailImage()
{
    $firstMedia = $this->getMedia('images')->first();
    return $firstMedia ? url($firstMedia->getUrl('milder')) : null;
}

public function setImages()
    {
        try {
            // Get media entries for this design
            $mediaEntries = Media::where('model_type', 'App\Models\Design')
                                 ->where('model_id', $this->id)
                                 ->orderBy('order_column')
                                 ->get();

            // Initialize an array to hold image URLs
            $imageUrls = [];

            foreach ($mediaEntries as $entry) {
                $url = 'storage/' . $entry->id . '/conversions/' . $entry->filename . '-mild.jpg';
                if ($entry->order_column == 1) {
                    // Set the main image URL
                    $this->image_url = $url;
                } else {
                    // Add to the images array
                    $imageUrls[] = $url;
                }
            }

            // Set the images property
            $this->images = $imageUrls;
        } catch (\Exception $e) {
            \Log::error('Image processing failed: ' . $e->getMessage());
            throw $e; // Re-throw the exception if needed
        }
    }
    
    public function setDetails() {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY')
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ["role" => "user", "content" => $this->generatePrompt()]
            ],
            'temperature' => 0.7
        ]);

        if ($response->successful()) {
            $this->details = $response->json()['choices'][0]['message']['content'];
            $this->save();
        } else {
            // Handle API request failure, log error details for debugging
            echo('OpenAI API request failed: ' . $response->body());
            die();
        }
    }

    public function setDefaultRef() {
        $json = json_decode($this->details, true);
        $this->defaultRef = $json['defaultRef'];
        $this->defaultParent = $json['defaultParent'];
    }

    protected function generatePrompt() {
        // Generate a prompt based on the product's existing attributes
        return "Write a 150 character product description for a random house";
    }

    public function getThumbnailAttribute()
    {
        $media = $this->getMedia('images')->first();
        return $media ? $media->getUrl('thumb') : null;
    }

    public function setMaterialDescription() {
        $code = $this->category[0]["category"];
        $materialDescriptions = [
            "df_cat_1" => "Дом из профилированного бруса",
              "df_cat_2" => "Баня из клееного бруса",
              "df_cat_3" => "Дом из блоков",
              "df_cat_4" => "Дом из бревна",
              "df_cat_5" => "Баня из бруса",
              "df_cat_6" => "Баня из бруса",
              "df_cat_7" => "Баня из бревна",
              "df_cat_8" => "Дом-баня из бруса",
              "df_cat_9" => "Дом из бруса камерной сушки",
              "df_cat_10" => "Дом из клееного бруса",
              "df_cat_11" => "Баня с бассейном",
              "df_cat_12" => "Каркасный дом",
              "df_cat_13" => "Баня из бревна",
              "df_cat_14" => "Баня из бревна",
              "df_cat_15" => "Баня из бруса",
              "df_cat_16" => "Баня из бруса",
              "df_cat_17" => "Дачный дом",
              "df_cat_18" => "Дом-баня из бревна",
              "df_cat_19" => "Дом из бревна",
              "df_cat_20" => "Дом из бревна",
              "df_cat_21" => "Дом из бруса",
              "df_cat_22" => "Дом из бруса"
            ];
        $this->materialDescription = $materialDescriptions[$code];
    }
    
    public function setDescription(Design $design) {
        $homeCategories = [1, 3, 4, 9, 10, 12, 17, 19, 20, 21, 22];
        $saunaCategories = [2, 5, 6, 7, 11, 13, 14, 15, 16];
        $mixedCategories = [18, 8];
    
        $hasHome = false;
        $hasSauna = false;
        $hasMixed = false;
        
        $size = $design->size; // Assuming $size is a property of the design

    $categories = $design->category;
    
        foreach ($categories as $category) {
            $categoryId = intval(str_replace('df_cat_', '', $category)); // Extract the numeric part
    
            if (in_array($categoryId, $mixedCategories)) {
                $hasMixed = true;
                break;
            }
    
            if (in_array($categoryId, $homeCategories)) {
                $hasHome = true;
            } elseif (in_array($categoryId, $saunaCategories)) {
                 $hasSauna = true;
            }
                
        }
    
        if ($hasMixed || ($hasHome && $hasSauna)) {
            $design->description = "Дом/Баня-$size";
        } elseif ($hasHome) {
            $design->description = "Дом-$size";
        } elseif ($hasSauna) {
            $design->description = "Баня-$size";
        }
    }
    
    public function setPrice(Design $design) {
        //details is a json that contains price element that should be set as "price"
        try {
            $setting = Setting::where('key', 'display_prices')->first();
            $displayPrices = $setting->value;
            $details = json_decode($design->details, true);
            $price = $details['price'][$displayPrices];
            $price = round($price, 2);
        } catch (\Exception $e) {
            $price = 99999;
        }
        $design->price = $this->formatPrice($price);
    }
    
    public function formatPrice($price) {
            return number_format($price, 2);
    }
    
    
    /*
    public function setMainCategory(Design $design) {
        dd($design->category);
        $design->main_category = $design->category[0]["category"];
    }
    */

 public function mainPicture()
    {
        return $this->getFirstMediaUrl('pictures');
    }

public function getShortDescriptionAttribute()
{
        return $this->meta->short_description;
}

public function setShortDescriptionAttribute($values)
{
        $this->meta->short_description = $values;
}

public function fullExcelTest() {
     // Fetch associated costs and set values in the spreadsheet
     $template = AssociatedCostModel::where('id', 7)->firstOrFail();
     $spreadsheet = IOFactory::load(storage_path('templates/late_2.xlsx'));
    $sheet = $spreadsheet->getActiveSheet();
     $associatedCosts = $template->associatedCosts;
     foreach ($associatedCosts as $cost) {
         $cell = $cost["p_code"];
         $value = $cost["p_cell"];
         $sheet->setCellValue($cell, $value);
     }
     //Кровля мягкая
     $sheet->setCellValue('D283', $this->roofSquare);
     $sheet->setCellValue('D284', $this->srCherep);
     $sheet->setCellValue('D285', $this->srKover);
     $sheet->setCellValue('D286', $this->srKonK);
     $sheet->setCellValue('D287', $this->srMastika1);
     $sheet->setCellValue('D288', $this->srMastika);
     $sheet->setCellValue('D289', $this->srKonShir);
     $sheet->setCellValue('D290', $this->srKonOneSkat);
     $sheet->setCellValue('D291', $this->srPlanVetr);
     $sheet->setCellValue('D292', $this->srPlanK);
     $sheet->setCellValue('D293', $this->srKapelnik);
     $sheet->setCellValue('D294', $this->srEndn);
     $sheet->setCellValue('D295', $this->srGvozd);
     $sheet->setCellValue('D296', $this->srSam70);
     $sheet->setCellValue('D297', $this->srPack);
     $sheet->setCellValue('D298', $this->srIzospanAM);
     $sheet->setCellValue('D299', $this->srIzospanAM35);
     $sheet->setCellValue('D300', $this->srLenta);
     $sheet->setCellValue('D301', $this->srRokvul);
     $sheet->setCellValue('D302', $this->srIzospanB);
     $sheet->setCellValue('D303', $this->srIzospanB35);
     $sheet->setCellValue('D304', $this->srPrimUgol);
     $sheet->setCellValue('D305', $this->srPrimNakl);
     $sheet->setCellValue('D306', $this->srOSB);
     $sheet->setCellValue('D307', $this->srAero);
     $sheet->setCellValue('D308', $this->srAeroSkat);
     $sheet->setCellValue('D309', $this->srtropValue);
     
     //dd($sheet);
     
     // Create a writer to save the spreadsheet
     $writer = new Xlsx($spreadsheet);
     
     // Define the file name and path in the storage
     $fileName = $this->id . '_foundation_lenta_' . time() . '.xlsx';
     $path = '/app/public/files/' . $this->id;
     File::ensureDirectoryExists(storage_path($path));
     $filePath = $path . "/" . $fileName;

     // Save the file to storage
     $writer->save(storage_path("$filePath"));
     
     $returnArray = [
         "publicPath" => '/storage/files/' . $this->id . '/' . $fileName,
         "fileName" => $fileName
         ];
     // Return a response for download
     return $returnArray;
 }

public function foundationLentaExcel($tape)
    {
        // Load the template file
        
        $tapeWidth = $tape[4];
        if ($tapeWidth == 1) {
             $tapeWidth = 10;
        }
        $tapeWidth = $tapeWidth/10;
        $tapeLength = $tapeWidth-0.1;
        $spreadsheet = IOFactory::load(storage_path('templates/abc.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('D4', $this->lfLength);
        $sheet->setCellValue('D5', $tapeWidth);
        $sheet->setCellValue('D8', $tapeLength);
        $sheet->setCellValue('D9', $this->lfAngleX);
        $sheet->setCellValue('D10', $this->lfAngleT);
        $sheet->setCellValue('D11', $this->lfAngleG);
        $sheet->setCellValue('D12', $this->lfAngle45);
        $sheet->setCellValue('D14', 0.2);
        $sheet->setCellValue('D15', 0.2);
        $sheet->setCellValue('D16', $this->mfSquare);
        $sheet->setCellValue('D18', $this->vfCount);
        
        
        // Fetch associated costs and set values in the spreadsheet
        $template = AssociatedCostModel::where('id', 7)->firstOrFail();
        $associatedCosts = $template->associatedCosts;
        foreach ($associatedCosts as $cost) {
            $cell = $cost["p_code"];
            $value = $cost["p_cell"];
            $sheet->setCellValue($cell, $value);
        }
        //Кровля мягкая
        $sheet->setCellValue('D283', $this->roofSquare);
        $sheet->setCellValue('D284', $this->srCherep);
        $sheet->setCellValue('D285', $this->srKover);
        $sheet->setCellValue('D286', $this->srKonK);
        $sheet->setCellValue('D287', $this->srMastika1);
        $sheet->setCellValue('D288', $this->srMastika);
        $sheet->setCellValue('D289', $this->srKonShir);
        $sheet->setCellValue('D290', $this->srKonOneSkat);
        $sheet->setCellValue('D291', $this->srPlanVetr);
        $sheet->setCellValue('D292', $this->srPlanK);
        $sheet->setCellValue('D293', $this->srKapelnik);
        $sheet->setCellValue('D294', $this->srEndn);
        $sheet->setCellValue('D295', $this->srGvozd);
        $sheet->setCellValue('D296', $this->srSam70);
        $sheet->setCellValue('D297', $this->srPack);
        $sheet->setCellValue('D298', $this->srIzospanAM);
        $sheet->setCellValue('D299', $this->srIzospanAM35);
        $sheet->setCellValue('D300', $this->srLenta);
        $sheet->setCellValue('D301', $this->srRokvul);
        $sheet->setCellValue('D302', $this->srIzospanB);
        $sheet->setCellValue('D303', $this->srIzospanB35);
        $sheet->setCellValue('D304', $this->srPrimUgol);
        $sheet->setCellValue('D305', $this->srPrimNakl);
        $sheet->setCellValue('D306', $this->srOSB);
        $sheet->setCellValue('D307', $this->srAero);
        $sheet->setCellValue('D308', $this->srAeroSkat);
        $sheet->setCellValue('D309', $this->srtropValue);
        
        //dd($sheet);
        
        // Create a writer to save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        
        // Define the file name and path in the storage
        $fileName = $this->id . '_foundation_lenta_' . time() . '.xlsx';
        $path = '/app/public/files/' . $this->id;
        File::ensureDirectoryExists(storage_path($path));
        $filePath = $path . "/" . $fileName;

        // Save the file to storage
        $writer->save(storage_path("$filePath"));
        
        $returnArray = [
            "publicPath" => '/storage/files/' . $this->id . '/' . $fileName,
            "fileName" => $fileName
            ];
        // Return a response for download
        return $returnArray;
    }
    

public function foundationLentaExcelTest($tape)
    {
        // Load the template file
        
        $tapeWidth = $tape[4];
        if ($tapeWidth == 1) {
             $tapeWidth = 10;
        }
        $tapeWidth = $tapeWidth/10;
        $tapeLength = $tapeWidth-0.1;
        $spreadsheet = IOFactory::load(storage_path('templates/foundation_lenta_tmp2.xlsx'));
        
        // Manipulate the spreadsheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('D4', $this->lfLength);
        $sheet->setCellValue('D5', $tapeWidth);
        $sheet->setCellValue('D8', $tapeLength);
        $sheet->setCellValue('D9', $this->lfAngleX);
        $sheet->setCellValue('D10', $this->lfAngleT);
        $sheet->setCellValue('D11', $this->lfAngleG);
        $sheet->setCellValue('D12', $this->lfAngle45);
        $sheet->setCellValue('D14', 0.2);
        $sheet->setCellValue('D15', 0.2);
        $sheet->setCellValue('D16', $this->mfSquare);
        
        
        
        // Create a writer to save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        
        // Define the file name and path in the storage
        $fileName = $this->id . '_foundation_lenta_' . time() . '.xlsx';
        $path = '/app/public/files/' . $this->id;
        File::ensureDirectoryExists(storage_path($path));
        $filePath = $path . "/" . $fileName;

        // Save the file to storage
        $writer->save(storage_path("$filePath"));
        
        $returnArray = [
            "publicPath" => '/storage/files/' . $this->id . '/' . $fileName,
            "fileName" => $fileName
            ];
        // Return a response for download
        return $returnArray;
    }

    // Add this method to the Design model
    public function seo()
    {
        return $this->hasOne(DesignSeo::class);
    }

    public function logs()
    {
        return $this->morphMany(PortalLog::class, 'loggable');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($design) {
            $design->slug = self::generateSlug($design);
        });

        static::updating(function ($design) {
            if ($design->isDirty(['title', 'size', 'length', 'width'])) {
                $design->slug = self::generateSlug($design);
            }
        });
    }

    public static function generateSlug($design)
    {
        $prefix = self::getSlugPrefix($design->title);
        $size = number_format($design->size, 1, '.', '');
        $length = number_format($design->length, 1, '.', '');
        $width = number_format($design->width, 1, '.', '');

        return "{$prefix}-{$size}m2-{$length}m-na-{$width}m";
    }

    private static function getSlugPrefix($title)
    {
        $prefixMap = [
            'Д-ОЦБ' => 'dom-iz-brevna',
            'Д-ПБ' => 'dom-iz-brusa',
            'Б-ОЦБ' => 'banya-iz-brevna',
            'Б-ПБ' => 'banya-iz-brusa',
        ];

        foreach ($prefixMap as $key => $value) {
            if (str_starts_with($title, $key)) {
                return $value;
            }
        }

        return 'design'; // Default prefix if no match found
    }
}

