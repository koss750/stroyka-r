<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Http\Controllers\RuTranslationController as Translator;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Laravel\Nova\Fields\Number;

class Setting extends Resource
{
    public static $model = \App\Models\Setting::class;

    public static $title = 'name';

    public static $search = [
        'id', 'key', 'name', 'value',
    ];

    public static function label()
    {
        return Translator::translate('legacy_settings_menu');
    }

    public function fields(Request $request)
    {

        $fields = [
            Text::make('Название', 'name'),
            Text::make('Код', 'key')->onlyOnIndex(),
        ];
        $additionalFields = $this->getValueField();
        $fields = array_merge($fields, $additionalFields);
        $furtherFields = [
            Textarea::make('Описание', 'description')->onlyOnDetail(),
            Select::make('Тип', 'type')->options([
                'text' => 'Текст',
                'select' => 'Выбор',
                'multiple_select' => 'Множественный выбор',
                'key_value' => 'Пары ключ-значение',
                'nested_select' => 'Вложенный выбор',
                'nested_boolean' => 'Вложенный булевый',
            ])->hideFromIndex(),
        ];
        return array_merge($fields, $furtherFields);
    }

    protected function getValueField()
    {
        if ($this->key == 'special') {
            return [
                SimpleRepeatable::make(Translator::translate('skatLabel'), 'value', [
                    Text::make('Заголовок', 'title')->displayUsing(function ($val) {
                        return Translator::translate('setting_label_' . $val);
                    }),
                    Number::make('0-50'),
                    Number::make('51-100'),
                    Number::make('101-150'),
                    Number::make('151-200'),
                    Number::make('201-250'),
                    Number::make('251-300'),
                    Number::make('300+')
                ])->canAddRows(true)
                  ->canDeleteRows(false)
                  ->addRowLabel("+ тип цен")
                  ->hideFromIndex()
            ];
        }
        switch ($this->type) {
            case 'text':
                $fields = [
                    Text::make('Значение', 'value')
                    ->resolveUsing(function ($value) {
                        return Translator::translate('setting_label_' . $value);
                    })
                    ->hideFromIndex()
                ];
            case 'select':
                $options = $this->options ?? [];
                $fields = [
                    Select::make('Значение', 'value')
                    ->options(array_combine($options, array_map(function($option) {
                        return Translator::translate('setting_label_' . $option);
                    }, $options)))
                    ->resolveUsing(function ($value) {
                        $decodedValue = json_decode($value, true);
                        return is_array($decodedValue) 
                            ? array_map(function($v) { return Translator::translate('setting_label_' . $v); }, $decodedValue)
                            : Translator::translate('setting_label_' . $value);
                    })
                    ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                            $model->{$attribute} = json_encode($request->input($attribute));
                        })->hideFromIndex()
                ];
            case 'multiple_select':
                $options = $this->options ?? [];
                $fields = [
                    Select::make('Значение', 'value')
                    ->options(array_combine($options, array_map(function($option) {
                        return Translator::translate('setting_label_' . $option);
                    }, $options)))
                    //->multiple()
                    ->resolveUsing(function ($value) {
                        $decodedValue = json_decode($value, true);
                        return array_map(function($v) {
                            return Translator::translate('setting_label_' . $v);
                        }, $decodedValue ?: []);
                    })
                    ->hideFromIndex()
                ];
            case 'key_value':
            case 'nested_select':
            case 'nested_boolean':
                $fields = [
                    Code::make('Значение', 'value')
                    ->json()
                    ->resolveUsing(function ($value) {
                        $decodedValue = json_decode($value, true);
                        return $this->translateNestedValues($decodedValue);
                    })
                    ->hideFromIndex()
                ];
            default:
                $fields = [
                    Text::make('Значение', 'value')
                    ->resolveUsing(function ($value) {
                        return 0;//Translator::translate('setting_label_' . $value);
                    })
                    ->hideFromIndex()
                ];
        }
        return $fields;
    }

    protected function translateNestedValues($value)
    {
        if (is_array($value)) {
            return array_map(function ($v) {
                return $this->translateNestedValues($v);
            }, $value);
        } else {
            return Translator::translate('setting_label_' . $value);
        }
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderBy('name');
    }
}