<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use App\Nova\Filters\CategoryFilter;
use Illuminate\Support\Facades\Lang;
use App\Nova\Floor;
use App\Nova\Room;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\MorphToMany;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Laravel\Nova\Http\Requests\NovaRequest;
use Handleglobal\NestedForm\NestedForm;
use InteractionDesignFoundation\HtmlCard\HtmlCard;
use Laravel\Nova\Card;
use Laravel\Nova\Panel;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use HasTranslations;

class Design2 extends Resource
{
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
                    "df_room_34" => "Столовая"
                    ];
        case "materialType":
            return [
                    "df_mat_0" => "Не установлено",
                    "df_mat_1" => "Бревно",
                    "df_mat_2" => "Брус",
                    "df_mat_3" => "Блочный",
                    "df_mat_4" => "Каркасный",
                    "df_mat_5" => "Фарферк"
                    ];
    }
}



    public function fields(Request $request)
    {
  
        return [
            ID::make()->sortable()->hide(),
            Text::make($this->translateKey('title'), 'title'),
            
            Panel::make('Основные свойства', [
                
                Text::make(__('Category'), function () {
                return $this->translateCode("category", $this->category);
                    })->exceptOnForms(),
                Select::make(__('Category'), 'category')->options($this->translatedSelects("category"))->onlyOnForms(),
                Number::make($this->translateKey('size'), 'size')->step(0.01),
                Number::make($this->translateKey('length'), 'length')->step(0.01),
                Number::make($this->translateKey('width'), 'width')->step(0.01),
                Text::make($this->translateKey('code'), 'code'),
                Number::make($this->translateKey('numOrders'), 'numOrders')->step(0.01),
                Text::make(__('materialType'), function () {
                return $this->translateCode("category", $this->category);
                    })->onlyOnDetail(),
                Select::make(__('materialType'), 'materialType')->options($this->translatedSelects("materialType"))->onlyOnForms(),
                Text::make($this->translateKey('baseType'), 'baseType'),
                //Text::make($this->translateKey('roofType'), 'roofType'),
                Number::make($this->translateKey('roofSquare'), 'roofSquare')->step(0.01),
                Number::make($this->translateKey('mainSquare'), 'mainSquare')->step(0.01),
                Text::make($this->translateKey('baseLength'), 'baseLength'),
                Text::make($this->translateKey('baseD20'), 'baseD20'),
                Text::make($this->translateKey('baseD20F'), 'baseD20F'),
                Text::make($this->translateKey('baseD20Rub'), 'baseD20Rub'),
                Text::make($this->translateKey('baseD20RubF'), 'baseD20RubF'),
                Text::make($this->translateKey('baseBalk1'), 'baseBalk1'),
                Text::make($this->translateKey('baseBalkF'), 'baseBalkF'),
                Text::make($this->translateKey('baseBalk2'), 'baseBalk2'),
                ]),
                
            Panel::make('Помещения', [
                
                SimpleRepeatable::make('Floors', 'floorsList', [
            Select::make('floor')->options([
                "f0" => 'Цокольный',
                "f1" => 'Первый',
                "f2" => 'Второй',
                "f3" => 'Третий',
                
            ]),
            SimpleRepeatable::make('Rooms', 'roomsList', [
                Select::make('type')->options([
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
                    "df_room_34" => "Столовая"
                    ]),
                    Number::make("Ширина", "width")->step(0.01),
                    Number::make('Длинна', "length")->step(0.01),
                    ])->addRowLabel("+ комната"),
                ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true)
                  ->addRowLabel("+ этаж")
                  
                    //NestedForm::make('Rooms'),
                
                ]),
            
            Panel::make('Стены и прорубы', [
                
                Number::make($this->translateKey('wallsOut'), 'wallsOut')->step(0.01),
                Number::make($this->translateKey('wallsIn'), 'wallsIn')->step(0.01),
                Number::make($this->translateKey('wallsPerOut'), 'wallsPerOut')->step(0.01),
                Number::make($this->translateKey('wallsPerIn'), 'wallsPerIn')->step(0.01),
                Text::make($this->translateKey('rubRoof'), 'rubRoof'),
                
                ]),
                
            Panel::make('Скаты крыши', [
                SimpleRepeatable::make('skatLabel', 'skatList', [
                Number::make("Ширина", "width")->step(0.01),
                Number::make('Длинна', "length")->step(0.01),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true),
            ]),
            
            Panel::make('Пирог кровли', [
                
                Number::make($this->translateKey('stropValue'), 'stropValue')->step(0.01),
                ]),
                
            Panel::make('Стропила', [
                SimpleRepeatable::make('stropLable', 'stropList', [
                Number::make("Ширина", "width")->step(0.01),
                Number::make('Длина', "length")->step(0.01),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true),
            ]),
            
            Panel::make('Ендовы', [
                SimpleRepeatable::make('endovLable', 'endovList', [
                Number::make("Скаты стропила", "quantity")->step(0.01),
                Number::make('Длина', "length")->step(0.01),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true),
            ]),
            
            Panel::make('Кровля из металлочерепицы', [
                SimpleRepeatable::make('metaLable', 'metaList', [
                Number::make("Длина листа", "width")->step(0.01),
                Number::make('Количество', "quantity")->step(0.01),
              ])
                  ->canAddRows(true) // Optional, true by default
                  ->canDeleteRows(true),
                
                Text::make($this->translateKey('srKonShir'), 'srKonShir'),
                Text::make($this->translateKey('srKonOneSkat'), 'srKonOneSkat'),
                Text::make($this->translateKey('srPlanVetr'), 'srPlanVetr'),
                Text::make($this->translateKey('srPlanK'), 'srPlanK'),
                Text::make($this->translateKey('srKapelnik'), 'srKapelnik'),
                Text::make($this->translateKey('srEndn'), 'srEndn'),
                Text::make($this->translateKey('srEndv'), 'srEndv'),
                Text::make($this->translateKey('srGvozd'), 'srGvozd'),
                Text::make($this->translateKey('srSam70'), 'srSam70'),
                Text::make($this->translateKey('srPack'), 'srPack'),
                Text::make($this->translateKey('srIzospanAM'), 'srIzospanAM'),
                Text::make($this->translateKey('srIzospanAM35'), 'srIzospanAM35'),
                Text::make($this->translateKey('srLenta'), 'srLenta'),
                Text::make($this->translateKey('srRokvul'), 'srRokvul'),
                Text::make($this->translateKey('srIzospanB'), 'srIzospanB'),
                Text::make($this->translateKey('srIzospanB35'), 'srIzospanB35'),
                Text::make($this->translateKey('srPrimUgol'), 'srPrimUgol'),
                Text::make($this->translateKey('srPrimNakl'), 'srPrimNakl'),
                Text::make($this->translateKey('srOSB'), 'srOSB'),
                Text::make($this->translateKey('srAero'), 'srAero'),
                Text::make($this->translateKey('srAeroSkat'), 'srAeroSkat'),
                Text::make($this->translateKey('srUtep150'), 'srUtep150'),
                Text::make($this->translateKey('srUtep200'), 'srUtep200'),
                
            ]),
                
            Panel::make('Кровля мягкая', [
                
            Text::make($this->translateKey('srCherep'), 'srCherep'),
            Text::make($this->translateKey('srKover'), 'srKover'),
            Text::make($this->translateKey('srKonK'), 'srKonK'),
            Text::make($this->translateKey('srMastika1'), 'srMastika1'),
            Text::make($this->translateKey('srMastika'), 'srMastika'),
            Text::make($this->translateKey('mrKon'), 'mrKon'),
            Text::make($this->translateKey('mrPlanVetr'), 'mrPlanVetr'),
            Text::make($this->translateKey('mrPlanKar'), 'mrPlanKar'),
            Text::make($this->translateKey('mrKapelnik'), 'mrKapelnik'),
            Text::make($this->translateKey('mrEndn'), 'mrEndn'),
            Text::make($this->translateKey('mrEndv'), 'mrEndv'),
            Text::make($this->translateKey('mrSam35'), 'mrSam35'),
            Text::make($this->translateKey('mrSam70'), 'mrSam70'),
            Text::make($this->translateKey('mrPack'), 'mrPack'),
            Text::make($this->translateKey('mrIzospanAM'), 'mrIzospanAM'),
            Text::make($this->translateKey('mrIzospanAM35'), 'mrIzospanAM35'),
            Text::make($this->translateKey('mrLenta'), 'mrLenta'),
            Text::make($this->translateKey('mrRokvul'), 'mrRokvul'),
            Text::make($this->translateKey('mrIzospanB'), 'mrIzospanB'),
            Text::make($this->translateKey('mrIzospanB35'), 'mrIzospanB35'),
            Text::make($this->translateKey('mrPrimUgol'), 'mrPrimUgol'),
            Text::make($this->translateKey('mrPrimNakl'), 'mrPrimNakl'),
            Text::make($this->translateKey('mrUtep200'), 'mrUtep200'),
            Text::make($this->translateKey('mrUtep150'), 'mrUtep150'),
                
                ]),
                
            Panel::make('Водосточка пластиковая', [
                
                Text::make(__($this->translateKey('pvPart1')), 'pvPart1'),
                Text::make(__($this->translateKey('pvPart2')), 'pvPart2'),
                Text::make(__($this->translateKey('pvPart3')), 'pvPart3'),
                Text::make(__($this->translateKey('pvPart4')), 'pvPart4'),
                Text::make(__($this->translateKey('pvPart5')), 'pvPart5'),
                Text::make(__($this->translateKey('pvPart6')), 'pvPart6'),
                Text::make(__($this->translateKey('pvPart7')), 'pvPart7'),
                Text::make(__($this->translateKey('pvPart8')), 'pvPart8'),
                Text::make(__($this->translateKey('pvPart9')), 'pvPart9'),
                Text::make(__($this->translateKey('pvPart10')), 'pvPart10'),
                Text::make(__($this->translateKey('pvPart11')), 'pvPart11'),
                Text::make(__($this->translateKey('pvPart12')), 'pvPart12'),
                
                
                ]),
                
            Panel::make('Водосточка металлическая', [
                
                Text::make(__($this->translateKey('mvPart1')), 'mvPart1'),
                Text::make(__($this->translateKey('mvPart2')), 'mvPart2'),
                Text::make(__($this->translateKey('mvPart3')), 'mvPart3'),
                Text::make(__($this->translateKey('mvPart4')), 'mvPart4'),
                Text::make(__($this->translateKey('mvPart5')), 'mvPart5'),
                Text::make(__($this->translateKey('mvPart6')), 'mvPart6'),
                Text::make(__($this->translateKey('mvPart7')), 'mvPart7'),
                Text::make(__($this->translateKey('mvPart8')), 'mvPart8'),
                Text::make(__($this->translateKey('mvPart9')), 'mvPart9'),
                Text::make(__($this->translateKey('mvPart10')), 'mvPart10'),
                Text::make(__($this->translateKey('mvPart11')), 'mvPart11'),
                Text::make(__($this->translateKey('mvPart12')), 'mvPart12'),
                
                ]),
                
            Panel::make('Фундамент ленточный', [
                
                 Text::make($this->translateKey('lfLength'), 'lfLength'),
                Text::make($this->translateKey('lfAngleG'), 'lfAngleG'),
                Text::make($this->translateKey('lfAngleT'), 'lfAngleT'),
                Text::make($this->translateKey('lfAngleX'), 'lfAngleX'),
                Text::make($this->translateKey('lfAngle45'), 'lfAngle45'),
                
                ]),
                
            Panel::make('Фундамент винтовой/жб', [
                
                Text::make($this->translateKey('vfLength'), 'vfLength'),
                Number::make($this->translateKey('vfCount'), 'vfCount')->step(0.01),
                Text::make($this->translateKey('vfBalk'), 'vfBalk'),
                
                ]),
                
            Panel::make('Фундамент монолитная плита', [
                
                 Text::make($this->translateKey('mfSquare'), 'mfSquare'),
                
                ]),
                
            Panel::make('Изображения', [
                Images::make('Main image', 'images') // second parameter is the media collection name
            ->conversionOnIndexView('thumb') // conversion used to display the image
            //,->rules('required'), // validation rules
        //NestedForm::make('Floors')->heading("helo"),
                ]),
                
            Panel::make('SEO оптимизация', [
                Text::make($this->translateKey('MetaTitle'), 'meta1'),
                Text::make($this->translateKey('MetaKeywords'), 'meta2'),
                Text::make($this->translateKey('MetaDesc'), 'meta3'),
                Text::make($this->translateKey('MetaHeader'), 'meta4'),
                ]),
        ];
    }

    public function filters(Request $request)
        {
            return [
                new CategoryFilter(),
            ];
        }

	private function translateKey($key) {
		$translations = [
		    "title" => "Название Проекта",
			"category" => "Категория",
"size" => "Общая площадь",
"length" => "Длина",
"width" => "Ширина",
"code" => "ID проекта",
"numOrders" => "Количество заказов",
"popular" => "",
"prefix" => "",
"price" => "",
"materialType" => "Тип материала",
"floors" => "Брев",
"baseType" => "Этажность",
"roofType" => "Тип Крыши",
"roofSquare" => "S крыши",
"mainSquare" => "Фундамент м.пог",
"baseLength" => "База ОЦБ 200 раб",
"baseD20" => "База ОЦБ 200 с отходом",
"baseD20F" => "База рубленное 200 раб",
"baseD20Rub" => "База рубленное 200 с отходом",
"baseD20RubF" => "База брус 145x140 раб",
"baseBalk1" => "База брус 145x140 с отходом",
"baseBalkF" => "База брус 145x140 раб",
"baseBalk2" => "База брус 145x140 с отходом",
"floorDown" => "Цокольный этаж",
"firstFloorSquare" => "Площадь 1й этаж",
"floorMid" => "Средний этаж",
"secondFloorSquare" => "Площадь средний этаж",
"floorMid2" => "Второй этаж",
"thrirdFloorSquare" => "Площадь",
"floorUp" => "Верхний этаж",
"roofFloorSquare" => "Крыша площадь",
"wallsOut" => "ОЦБ Стены м2",
"wallsIn" => "ПБ стены м2",
"wallsPerOut" => "ПБ Перерубы пог.м",
"wallsPerIn" => "ОЦБ перерубы пог.м",
"rubRoof" => "Кровля рубероид м2",
"stropValue" => "Объем стропил, м3",
"mrKon" => "Конек широкий шт",
"mrKonOneSkat" => "Конек односкатной крыши шт",
"mrPlanVetr" => "Планка ветровая шт",
"mrPlanKar" => "Карнизная планка шт",
"mrKapelnik" => "Капельники шт",
"mrEndn" => "Ендова нижняя шт",
"mrEndv" => "Ендова верхняя шт",
"mrSam35" => "Саморез 35 уп",
"mrSam70" => "Саморез 70 уп",
"mrPack" => "Упаковка шт",
"mrIzospanAM" => "Изоспан АМ 70м2, шт",
"mrIzospanAM35" => "Изоспан АМ 35м2, шт",
"mrLenta" => "Лента клеещая двухсторонняя шт",
"mrRokvul" => "Роквул скандик уп",
"mrIzospanB" => "Изоспан В 70м2, шт",
"mrIzospanB35" => "Изоспан В 35м2, шт",
"mrPrimUgol" => "Примыкание угловое, шт",
"mrPrimNakl" => "Примыкание накладное, шт",
"mrUtep200" => "Утепление кровли 150мм уп",
"mrUtep150" => "Утепление кровли 200мм уп",
"srCherep" => "Гибкая черепица Shnglas коллекция Сальса",
"srKover" => "Подкладочный ковер рул",
"srKonK" => "Конек карниз шт",
"srMastika1" => "Мастика 3.6 кг шт",
"srMastika" => "Мастика 12 кг шт",
"srKonShir" => "Конек широкий шт",
"srKonOneSkat" => "Конек односкатной крыши",
"srPlanVetr" => "Планка ветровая шт",
"srPlanK" => "Карнизная планка шт",
"srKapelnik" => "Капельники шт",
"srEndn" => "Ендова шт",
"srEndv" => "Ендова шт",
"srGvozd" => "Гвоздь кровельный уп",
"srSam70" => "Саморез 70 уп",
"srPack" => "Упаковка шт",
"srIzospanAM" => "Изоспан АМ 70м2, шт",
"srIzospanAM35" => "Изоспан АМ 35м2, шт",
"srLenta" => "Лента клеещая двухстороня шт=>",
"srRokvul" => "Роквул скандик уп",
"srIzospanB" => "Изоспан В 70м2, шт",
"srIzospanB35" => "Изоспан В 35м2, шт",
"srPrimUgol" => "Примыкание угловое, шт",
"srPrimNakl" => "Примыкание накладное, ш	",
"srOSB" => "OSB-3 9 мм лист",
"srAero" => "Аэратор конька шт",
"srAeroSkat" => "Аэратор скатный шт",
"srUtep150" => "Утепление кровли 200мм у",
"srUtep200" => "Утепление кровли 150мм у",
"pvPart1" => "Желоб 3м, шт",
"pvPart2" => "Соединитель желоба, шт",
"pvPart3" => "Кронштейн желоба, шт",
"pvPart4" => "Заглушка, шт",
"pvPart5" => "Воронка, шт",
"pvPart6" => "Колено, шт",
"pvPart7" => "Отвод, шт",
"pvPart8" => "Труба 3м, шт",
"pvPart9" => "Труба 1м, шт",
"pvPart10" => "Хомут трубы, шт",
"pvPart11" => "Муфта трубы, шт",
"pvPart12" => "Угол желоба 90*, шт",
"mvPart1" => "Желоб 3м, шт",
"mvPart2" => "Соединитель желоба, шт",
"mvPart3" => "Кронштейн желоба, шт",
"mvPart4" => "Заглушка, шт",
"mvPart5" => "Воронка, шт",
"mvPart6" => "Колено, шт",
"mvPart7" => "Отвод, шт",
"mvPart8" => "Труба 3м, шт",
"mvPart9" => "Труба 1м, шт",
"mvPart10" => "Хомут трубы, шт",
"mvPart12" => "Угол желоба 90* внутренний, шт",
"mvPart11" => "Угол желоба 90* внешний, шт",
"lfLength" => "Длина, пог. м",
"lfAngleG" => "Углы Г-образные, шт",
"lfAngleT" => "Углы Т-образные, шт",
"lfAngleX" => "Перекрестия +, шт",
"lfAngle45" => "Углы 45 градусов, шт",
"vfLength" => "Длина, пог.м",
"vfCount" => "количество свай, шт",
"vfBalk" => "объем бруса, м3",
"file" => "Изображение",
"mfSquare" => "площадь, м2",
"MetaTitle" => "МЕТА заголовок",
"MetaKeywords" => "META ключевые слова",
"MetaDesc" => "META описание",
"MetaHeader" => "Заголовок элемента",
"rooms" => "Комнаты"
		];
		if ($translations[$key]) {
			return $translations[$key];
		} else return $key;
	}
}