<?php

namespace App\Nova;

use Illuminate\Http\Request;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\RuTranslationController as Translator;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Filters\CategoryFilter;
use Illuminate\Support\Facades\Lang;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\KeyValue;
use App\Nova\Filters\TypeOfDesign;
use App\Nova\Actions\GenerateExcel;
use App\Nova\Actions\GenerateOSExcel;
//use App\Nova\Filters\DesignSEO;
use App\Nova\Actions\CheckPrices;
use Eminiarts\Tabs\Traits\HasTabs;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\MorphToMany;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Laravel\Nova\Http\Requests\NovaRequest;
use Handleglobal\NestedForm\NestedForm;
use InteractionDesignFoundation\HtmlCard\HtmlCard;
use Laravel\Nova\Card;
use Laravel\Nova\Panel;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use HasTranslations;
use Laravel\Nova\Fields\Heading;
use App\Nova\Filters\ActiveFilterAlt;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use App\Nova\Filters\AlmostReady;
use Laravel\Nova\Fields\HasOne;
use App\Nova\Actions\IndexDesign;
use App\Nova\PortalLog;
use App\Models\ProjectPrice;
use App\Nova\ProjectPrice as ProjectPriceNova;


class Design extends Resource
{
    use HasTabs;
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\Design>
	 */
	public static $model = 'App\Models\Design';

    public static $title = ('title');

    public static $search = [
        'id', 'title', 'category', 'code'
    ];
    
    private function translateCode($cat,$key) {
        return $this->translatedSelects($cat)[$key] ?? null;
    }
    
    private function formatJson($jsonValue, $overrides=false) {
        
                $jsonValue = json_decode(json_encode($jsonValue), true);
                if (is_array($jsonValue)) {
                    $formattedValue = [];
                    foreach ($jsonValue as $floorData) {
                        $formattedFloor = [];
                        foreach ($floorData as $propKey => $propVal) {
                            if ($overrides == true) $propKey = "quantity";
                            $formattedFloor[] = Translator::translate($propKey) . ': ' . $propVal;
                        }
                        $formattedValue[] = '--- ' . implode(', ', $formattedFloor);
                    }
                    $formattedValue = implode(' || ', $formattedValue);
                } else {
                    $formattedValue = [];
                }
                
                return $formattedValue;
        
    }
    
    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        
        $controller = new DesignController;
        $headers = $controller->getHeaders();
        return [
            new IndexDesign,
            new GenerateExcel,
            (new DownloadExcel)->withHeadings()
        ];
    }
    
    public function trimZero($value) {
        $output = (float)$value;
        if ($output == null) {
            return " ";
        } else return $output;
    }
    
    public function translatedSelects($cat)
{
    switch ($cat) {
        case "category":
            return [
                "df_cat_1" => "Дома из профилированного бруса",
                  "df_cat_2" => "Бани из клееного бруса",
                  "df_cat_3" => "Дома из блоков",
                  "df_cat_4" => "Дома из оцилиндрованного бревна",
                  "df_cat_5" => "Бани из бруса камерной сушки",
                  "df_cat_6" => "Бани из бруса сосна/ель",
                  "df_cat_7" => "Бани из оцилиндрованного бревна",
                  "df_cat_8" => "Дом-баня из бруса",
                  "df_cat_9" => "Дома из бруса камерной сушки",
                  "df_cat_10" => "Дома из клееного бруса",
                  "df_cat_11" => "Бани с бассейном",
                  "df_cat_12" => "Каркасные дома",
                  "df_cat_13" => "Бани из бревна кедра",
                  "df_cat_14" => "Бани из бревна лиственницы",
                  "df_cat_15" => "Бани из бруса кедра",
                  "df_cat_16" => "Бани из бруса лиственницы",
                  "df_cat_17" => "Дачные дома",
                  "df_cat_18" => "Дом-баня из бревна",
                  "df_cat_19" => "Дома из бревна кедра",
                  "df_cat_20" => "Дома из бревна лиственницы",
                  "df_cat_21" => "Дома из бруса кедра",
                  "df_cat_22" => "Дома из бруса лиственницы"
                ];
        case "rooms":
            return [
                    "df_room_1" => "Крыльцо",
                    "df_room_2" => "Терраса",
                    "df_room_3" => "Закрытая терраса",
                    "df_room_4" => "Навес",
                    "df_room_5" => "Гараж",
                    "df_room_6" => "Тамбур",
                    "df_room_7" => "Холл",
                    "df_room_8" => "Прихожая",
                    "df_room_9" => "Прачечная",
                    "df_room_10" => "С/У",
                    "df_room_11" => "Кухня",
                    "df_room_12" => "Кухня-гостиная",
                    "df_room_13" => "Гостиная",
                    "df_room_14" => "Котельная",
                    "df_room_15" => "Гардероб",
                    "df_room_16" => "Кабинет",
                    "df_room_17" => "Спальня",
                    "df_room_18" => "Комната",
                    "df_room_19" => "Гараж",
                    "df_room_20" => "Кладовая",
                    "df_room_21" => "Парная",
                    "df_room_22" => "Помывочная",
                    "df_room_23" => "Комната отдыха",
                    "df_room_24" => "Балкон",
                    "df_room_25" => "Антресоль",
                    "df_room_26" => "Второй свет",
                    "df_room_27" => "Помещение",
                    "df_room_28" => "Чердачное помещение",
                    "df_room_29" => "Хозблок",
                    "df_room_32" => "Коридор",
                    "df_room_33" => "Кухня-столовая",
                    "df_room_34" => "Столовая",
                    "df_room_35" => "Веранда",
                    "df_room_36" => "Купель",
                    "df_room_37" => "Раздевалка"
                    ];
        case "materialType":
            return [
                    "Не установлено" => "Не установлено",
                    "Бревно" => "Бревно",
                    "Брус" => "Брус",
                    "Блочный" => "Блочный",
                    "Каркасный" => "Каркасный",
                    "Фарферк" => "Фарферк"
                    ];
        case "baseType":
            return [
                    '1 этаж' => '1 этаж',
                '2 этажа' => '2 этажа',
                '2 этажа (мансарда)' => '2 этажа (мансарда)',
                '2 этажа + мансарда' => '2 этажа + мансарда',
                '3 этажа' => '3 этажа'
                    ];
    }
    
    //public function generatePanel 
}

    public function formatCategoryList($value) {
                
                    $string = '';
                    
                    if (!is_array($value) && !empty($value)) {
                        return $string;
                    } else if (is_array($value)) {
                        $string = Translator::translate($value[0]['category']);
                    
                        if (count($value) > 1) {
                            unset($value[0]);
                            foreach ($value as $item) {
                                $string .= ", " . Translator::translate($item['category']);
                            }
                            return $string;
                        } else {
                            return $string;
                        }
                        
                    }
    }


    public function fields(Request $request)
    {
        $controller = new DesignController;
        return [
            ID::make()->sortable()->hide(),

            // Add this Hidden field near the top of your fields array
            Hidden::make('details')->default(json_encode([
                "defaultRef" => 471,
                "defaultParent" => 211,
                "price" => 999
            ]))->hideFromIndex(),
            
            Panel::make('Главное', [
                
                Text::make(Translator::translate('title'), 'title')->rules('required')->sortable(),
                Text::make('Просмотров', 'view_count')->onlyOnIndex(),
                Boolean::make('Активен', 'active')->trueValue(true)->falseValue(false)->onlyOnForms(),

                Text::make('Прогресс', 'progress')->onlyOnIndex()->displayUsing(function ($value) {
                    return round($value * 100, 0) . '%';
                }),
                
                /*
                Text::make('Незаполненные поля', 'incompleteFields')->onlyOnDetail()->displayUsing(function ($value) {
                    return implode(', ', $value);
                }),
                */

                SimpleRepeatable::make(Translator::translate('Category'), 'category', [
                    Select::make(Translator::translate('Category'), 'category')->options($this->translatedSelects("category")),
                ])->rules('required')->onlyOnForms(),
                    
                Text::make(Translator::translate('Main_category'), 'category')->exceptOnForms()->displayUsing(function ($value) {
                
                    if (!is_array($value) && !empty($value)) {
                        return null;
                    }
                    else if (is_array($value)) {
                    // Translate the first category
                        $firstCategory = Translator::translate($value[0]['category']);
                    
                        // Count the additional categories
                        $additionalCount = count($value) - 1;
                    
                        if ($additionalCount > 0) {
                            return $firstCategory . " + " . $additionalCount . " " . Translator::translate('others');
                        } else {
                            return $firstCategory;
                        }
                    }
                    
                }),
                
                Text::make(Translator::translate('Main_category'), fn () => $this->formatCategoryList($this->category))
                ->onlyOnExport(),
                
                //size (Площадь)
                Text::make(Translator::translate('size'), 'size')->onlyOnForms()->rules('required')->hideFromIndex()->showOnExport(),
                Number::make(Translator::translate('size'), 'size')->sortable()->displayUsing(function ($value) {
                    return (float)($value);
                })->exceptOnForms(),

                //link to the design
                Text::make(Translator::translate('slug'), 'slug')->onlyOnDetail()->displayUsing(function ($value) {
                    return env('APP_URL') . '/project/' . $value;
                }),
                
                //length
                Text::make(Translator::translate('length'), 'length')->onlyOnForms()->hideFromIndex()->showOnExport(),
                Number::make(Translator::translate('length'), 'length')->sortable()->displayUsing(function ($value) {
                    return (float)($value);
                })->exceptOnForms()->hideFromIndex()->showOnExport(),
                //width
                Text::make(Translator::translate('width'), 'width')->onlyOnForms()->hideFromIndex()->showOnExport(),
                Number::make(Translator::translate('width'), 'width')->displayUsing(function ($value) {
                    return (float)($value);
                })->exceptOnForms()->hideFromIndex()->showOnExport(),
                
                Text::make(Translator::translate('code'), 'code')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('numOrders'), 'numOrders')->hideFromIndex()->showOnExport(),
                Select::make(Translator::translate('materialType'), 'materialType')->options($this->translatedSelects("materialType"))->hideFromIndex()->showOnExport(),
                Select::make(Translator::translate('baseType'), 'baseType')->options($this->translatedSelects("baseType"))->hideFromIndex()->showOnExport(),
                //Text::make(Translator::translate('baseType'), 'baseType')->exceptOnForms()->hideFromIndex()->showOnExport(),
                //Text::make(Translator::translate('roofType'), 'roofType'),
                Text::make(Translator::translate('roofSquare'), 'roofSquare')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('mainSquare'), 'mainSquare')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('stolby'), 'stolby')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('baseLength'), 'baseLength')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('baseD20'), 'baseD20')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('baseD20F'), 'baseD20F')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('baseD20Rub'), 'baseD20Rub')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('baseD20RubF'), 'baseD20RubF')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('baseBalk1'), 'baseBalk1')->hideFromIndex()->showOnExport(),
                ]),
                
            // Areas of floors as a SimpleRepeatable
            Panel::make(Translator::translate('areafl'), [
                
            SimpleRepeatable::make(Translator::translate('areafl'), 'areafl0', [
                Text::make(Translator::translate('areaflB'), 'Sfl0'),
                Text::make(Translator::translate('areafl1'), 'Sfl1'),
                Text::make(Translator::translate('areafl2'), 'Sfl2'),
                Text::make(Translator::translate('areafl3'), 'Sfl3'),
                Text::make(Translator::translate('areaflA'), 'Sfl4'),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->addRowLabel("указать")
                  ]),
                
            Panel::make('Помещения', [
                
                SimpleRepeatable::make('Помещения', 'floorsList', [
            Select::make('Этаж', 'floors')->options([
                'Цокольный' => 'Цокольный',
                'Первый' => 'Первый',
                'Второй' => 'Второй',
                'Третий' => 'Третий',
                'Чердак' => 'Чердак'
            ])->placeholder('Оставить пустым, если как выше'),
                Select::make('Тип пом-я', 'roomTypes')->options([
                "Крыльцо"    => "Крыльцо",
                "Терраса"    => "Терраса",
                "Закрытая терраса"    => "Закрытая терраса",
                "Навес"    => "Навес",
                "Гараж"    => "Гараж",
                "Тамбур"    => "Тамбур",
                "Холл"    => "Холл",
                "Прихожая"    => "Прихожая",
                "Прачечная"    => "Прачечная",
                 "С/У"     => "С/У",
                 "Кухня"     => "Кухня",
                 "Кухня-гостиная"     => "Кухня-гостиная",
                 "Гостиная"     => "Гостиная",
                 "Котельная"     => "Котельная",
                 "Гардероб"     => "Гардероб",
                 "Кабинет"     => "Кабинет",
                 "Спальня"     => "Спальня",
                 "Комната"     => "Комната",
                 "Гараж"     => "Гараж",
                 "Кладовая"     => "Кладовая",
                 "Парная"     => "Парная",
                 "Помывочная"     => "Помывочная",
                 "Комната отдыха"     => "Комната отдыха",
                 "Балкон"     => "Балкон",
                 "Антресоль"     => "Антресоль",
                 "Второй свет"     => "Второй свет",
                 "Помещение"     => "Помещение",
                 "Чердачное помещение"     => "Чердачное помещение",
                 "Хозблок"     => "Хозблок",
                 "Коридор"     => "Коридор",
                 "Кухня-столовая"     => "Кухня-столовая",
                 "Столовая"     => "Столовая",
                 "Веранда" => "Веранда",
                 "Купель" => "Купель",
                 "Раздевалка" => "Раздевалка"
                    ]),
                    Text::make("Ширина", "width"),
                    Text::make("Длина", "length"),
                    ]),
                  
                    //NestedForm::make('Rooms'),
                ]),
                
            Text::make('floorsList', fn () => $this->formatJson($this->floorsList))
                ->onlyOnExport(),
                
            
                  
            Panel::make('Стены и перерубы', [
                
                Text::make(Translator::translate('wallsOut'), 'wallsOut')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('wallsIn'), 'wallsIn')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('wallsPerOut'), 'wallsPerOut')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('wallsPerIn'), 'wallsPerIn')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('rubRoof'), 'rubRoof')->hideFromIndex()->showOnExport(),
                
                ]),
                
            Panel::make('Скаты крыши', [
                SimpleRepeatable::make(Translator::translate('skatLabel'), 'skatList', [
                Text::make("Ширина", "width"),
                Text::make('Длина', "length"),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true)
                  ->addRowLabel("+ скат"),
            ]),
            
            Text::make(Translator::translate('skatLabel'), fn () => $this->formatJson($this->skatList))
                ->onlyOnExport(),
            
            
            Panel::make('Пирог кровли', [
                
                Text::make(Translator::translate('stropValue'), 'stropValue')->hideFromIndex()->showOnExport(),
                ]),
                
            Panel::make('Стропила', [
                SimpleRepeatable::make(Translator::translate('stropLabel'), 'stropList', [
                Text::make("Скаты стропила, шт", "width"),
                Text::make('Длина', "length"),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true)
                  ->addRowLabel("+ стропила"),
            ]),
            
            Text::make(Translator::translate('stropLabel'), fn () => $this->formatJson($this->stropList, true))
                ->onlyOnExport(),
            
            Panel::make('Ендовы', [
                SimpleRepeatable::make(Translator::translate('endovLable'), 'endovList', [
                Text::make("Скаты, шт	", "quantity"),
                Text::make('Длина', "length"),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true)
                  ->addRowLabel("+ ендовы"),
            ]),
            
             Text::make(Translator::translate('endovLable'), fn () => $this->formatJson($this->endovList))
                ->onlyOnExport(),
            
            Panel::make('Кровля из металлочерепицы', [
                SimpleRepeatable::make(Translator::translate('metaLabel'), 'metaList', [
                Text::make("Длина листа", "width"),
                Text::make('Количество', "quantity"),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true)
                  ->addRowLabel("+ лист"),       
                  
            Text::make(Translator::translate('metaLabel'), fn () => $this->formatJson($this->metaList))
                ->onlyOnExport(),
            
        Text::make(Translator::translate('srKonShir'), 'srKonShir')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srKonOneSkat'), 'srKonOneSkat')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srPlanVetr'), 'srPlanVetr')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srPlanK'), 'srPlanK')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srKapelnik'), 'srKapelnik')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srEndn'), 'srEndn')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srEndv'), 'srEndv')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('mrSam35'), 'mrSam35')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srSam70'), 'srSam70')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srPack'), 'srPack')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srIzospanAM'), 'srIzospanAM')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srIzospanAM35'), 'srIzospanAM35')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srLenta'), 'srLenta')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srRokvul'), 'srRokvul')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srIzospanB'), 'srIzospanB')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srIzospanB35'), 'srIzospanB35')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srPrimUgol'), 'srPrimUgol')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srPrimNakl'), 'srPrimNakl')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srUtep150'), 'srUtep150')->hideFromIndex()->showOnExport(),
        Text::make(Translator::translate('srUtep200'), 'srUtep200')->hideFromIndex()->showOnExport(),
                
            ]),
                
            Panel::make('Кровля мягкая', [
                
            Text::make(Translator::translate('srCherep'), 'srCherep')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srKover'), 'srKover')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srKonK'), 'srKonK')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srMastika1'), 'srMastika1')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srMastika'), 'srMastika')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrKon'), 'mrKon')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrEndn'), 'mrEndn')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrPlanVetr'), 'mrPlanVetr')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrPlanKar'), 'mrPlanKar')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrKapelnik'), 'mrKapelnik')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrEndv'), 'mrEndv')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srGvozd'), 'srGvozd')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrSam70'), 'mrSam70')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrPack'), 'mrPack')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrIzospanAM'), 'mrIzospanAM')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrIzospanAM35'), 'mrIzospanAM35')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrLenta'), 'mrLenta')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrRokvul'), 'mrRokvul')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrIzospanB'), 'mrIzospanB')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrIzospanB35'), 'mrIzospanB35')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrPrimUgol'), 'mrPrimUgol')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrPrimNakl'), 'mrPrimNakl')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srOSB'), 'srOSB')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srAero'), 'srAero')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('srAeroSkat'), 'srAeroSkat')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrUtep200'), 'mrUtep200')->hideFromIndex()->showOnExport(),
            Text::make(Translator::translate('mrUtep150'), 'mrUtep150')->hideFromIndex()->showOnExport(),
            
            
                
                ]),
                
            Panel::make('Водосточка пластиковая', [
                
                Text::make(__(Translator::translate('pvPart1')), 'pvPart1')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart2')), 'pvPart2')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart3')), 'pvPart3')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart4')), 'pvPart4')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart5')), 'pvPart5')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart6')), 'pvPart6')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart7')), 'pvPart7')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart8')), 'pvPart8')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart9')), 'pvPart9')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart10')), 'pvPart10')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart11')), 'pvPart11')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart12')), 'pvPart12')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('pvPart13')), 'pvPart13')->hideFromIndex()->showOnExport(),
                
                
                ]),
                
            Panel::make('Водосточка металлическая', [
                
                Text::make(__(Translator::translate('mvPart1')), 'mvPart1')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart2')), 'mvPart2')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart3')), 'mvPart3')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart4')), 'mvPart4')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart5')), 'mvPart5')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart6')), 'mvPart6')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart7')), 'mvPart7')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart8')), 'mvPart8')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart9')), 'mvPart9')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart10')), 'mvPart10')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart12')), 'mvPart12')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart11')), 'mvPart11')->hideFromIndex()->showOnExport(),
                Text::make(__(Translator::translate('mvPart13')), 'mvPart13')->hideFromIndex()->showOnExport(),
                
                ]),
                
            Panel::make('Фундамент ленточный', [
                
                 Text::make(Translator::translate('lfLength'), 'lfLength')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('lfAngleG'), 'lfAngleG')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('lfAngleT'), 'lfAngleT')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('lfAngleX'), 'lfAngleX')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('lfAngle45'), 'lfAngle45')->hideFromIndex()->showOnExport(),
                
                ]),
                
            Panel::make('Фундамент винтовой/жб', [
                
                Text::make(Translator::translate('vfLength'), 'vfLength')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('vfCount'), 'vfCount')->hideFromIndex()->showOnExport(),
                Text::make(Translator::translate('vfBalk'), 'vfBalk')->hideFromIndex()->showOnExport(),
                
                ]),
                
            Panel::make('Фундамент монолитная плита', [
                
                 Text::make(Translator::translate('mfSquare'), 'mfSquare')->hideFromIndex()->showOnExport(),
                 Text::make(Translator::translate('outer_p'), 'outer_p')->hideFromIndex()->showOnExport(),

                ]),
                
            Panel::make('Изображения', [
                Images::make('Изображения', 'images') // second parameter is the media collection name
            ->conversionOnIndexView('jpg'),
        /*    ->withMeta(['extraAttributes' => [
        'fileInfo' => [
            'name' => function ($value, $disk, $resource) {
                return $resource->filename; // Replace 'name' with the actual attribute name of the file name
            }
            ]]]),*/
         // conversion used to display the image
            //->conversionOnDetailView('mild')
            //->singleImageRules('dimensions:min_width=1000')
            //->rules('required'), // validation rules
    //    NestedForm::make('Floors')->heading("helo")
                ]),
            Panel::make('SEO', [
                HasOne::make('SEO', 'seo', DesignSeo::class),
            ]),

            Panel::make('Логи', [
                HasMany::make('Действия', 'logs', PortalLog::class),
            ]),
               
            Panel::make('Цены', [
                HasMany::make('Цены', 'projectPrices', ProjectPriceNova::class),  
            ]),
            
                
                
        ];
    }
    public function seo()
    {
        return $this->hasOne(DesignSeo::class);
    }

    public function filters(Request $request)
        {
            return [
                new ActiveFilterAlt,
                new AlmostReady,
                new TypeOfDesign,
                //new DesignSEO
            ];
        }
    
    public static function availableForNavigation(Request $request)
        {
            return $request->user()->hasAccessToNovaResource('Designs');
        }

    public function authorizedToDelete(Request $request)
    {
        return true; // Or implement your authorization logic here
    }

}