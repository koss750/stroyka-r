<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Illuminate\Support\Facades\Redis;
use App\Models\InvoiceType;
use Laravel\Nova\Panel;
use App\Http\Controllers\RuTranslationController as Translator;
use App\Nova\Filters\ActiveFilter;
use Eminiarts\Tabs\Traits\HasTabs;
use Laravel\Nova\Fields\Select;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;
use App\Models\ProjectPrice;
use App\Nova\Filters\AlmostReady;
use Laravel\Nova\Fields\BooleanGroup;

class DesignNonAdmin extends Resource
{
    use HasTabs;

    public static $model = 'App\Models\Design';

    public static $title = 'title';

    public static $search = [
        'id', 'title'
    ];

    public function filters(Request $request) {
        return [
            new ActiveFilter(),
            new AlmostReady()
        ];
    }

    public static function label()
    {
        $labelCaption = 'Price_page_label';
        return Translator::translate($labelCaption); 
    }

    public function fields(Request $request)
    {
        $etiketka = number_format($this->etiketka, 0, ',', ' ');
        $etiketkaSeasonal = number_format($this->etiketka_seasonal, 0, ',', ' ');
        $fields = [
            Text::make('Title', 'title'),
            Number::make('Size', 'size')->onlyOnDetail(),
            Number::make('Этикетка (обычная)', function () use ($etiketka) {
                return $etiketka;
            }),
            Number::make('Этикетка (сезонные)', function () use ($etiketkaSeasonal) {
                return $etiketkaSeasonal;
            })
        ];

        $priceObjects = $this->getPriceObjects();

        foreach ($priceObjects as $area => $tabs) {
            $fields[] = Tabs::make($area, $tabs);
        }

        return $fields;
    }

    private function getPriceObjects()
{
    if (strpos(request()->fullUrl(), 'filters') !== false || request()->query('perPage')) {
        return [];
    }

    $invoiceTypes = InvoiceType::all();
    $invoiceTypeLabels = [];
    $priceObjects = [];

    foreach ($invoiceTypes as $invoiceType) {
        if ($invoiceType->sheetname != null || $invoiceType->nova_tab_label != null) {
            $area = $this->getAreaName($invoiceType->oldestParent->label);
            if ($invoiceType->nova_tab_label != null) {
                $section = $invoiceType->nova_tab_label;
            } else {
                $section = $invoiceType->oldestParent->label;
            }
            if ($invoiceType->nova_label != null) {
                $title = $invoiceType->nova_label;
            } else {
                $title = $invoiceType->title;
            }
            $sheetname = str_replace("Смета ", "", $invoiceType->sheetname);
            if ($invoiceType->label != null) {
                $invoiceTypeLabels[$section][] = [
                    'label' => $invoiceType->label,
                    'sheetname' => $sheetname,
                    'area' => $area,
                    'title' => $title
                ];
            }
        }
    }

    $keys = Redis::keys('*'. $this->id .'*');
    $updatedPrices = [];

    foreach ($keys as $key) {
        if ($key != "stroyka_" . $this->id) {
            $value = Redis::get($key);
            if ($value != null) {
                $key = str_replace('stroyka_' . $this->id . '_', '', $key);
                $updatedPrices[$key] = $value;
            } else {
                $invoiceType = InvoiceType::where('label', $key)->first();
                $price = ProjectPrice::where('design_id', $this->id)->where('invoice_type_id', $invoiceType->id)->first();
                if ($price != null) {
                    $key = str_replace('stroyka_' . $this->id . '_', '', $key);
                    $updatedPrices[$key] = $price->price;
                }
            }
        }
    }

    $fields = [];
    foreach ($invoiceTypeLabels as $section => $labels) {
        foreach ($labels as $label) {
            $area = $label['area'];
            if (isset($updatedPrices[$label['label']])) {
                $value = $updatedPrices[$label['label']];
                $fields[$area][$section][] = Text::make($label['title'], function () use ($value) {
                    $value = json_decode($value, true);
                    $material = number_format($value['material'], 0, ',', ' ');
                    $labour = number_format($value['labour'], 0, ',', ' ');
                    $total = number_format($value['total'], 0, ',', ' ');
                    return $material . " + $labour работы. Итого: $total";
                })->hideFromIndex();
            }
        }
    }

    foreach ($fields as $area => $priceObject) {
        foreach ($priceObject as $section => $sectionPriceObject) {
            $priceObjects[$area][] = Tab::make($section, $sectionPriceObject);
        }
    }

    return $priceObjects;
}
private function getDomokomplektyTabs($priceObjects)
    {
        return $priceObjects['Домокомплекты'] ?? [];
    }

    private function getAreaName($section)
    {
        switch ($section) {
            case "d":
                return "Домокомплекты";
            case "f":
            case "r":
                return "Фундамент и Кровля";
            default:
                return $section;
        }
    }

    public static function availableForNavigation(Request $request)
    {
        return true;
    }
}