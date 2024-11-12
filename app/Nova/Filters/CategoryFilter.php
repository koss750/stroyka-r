<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class CategoryFilter extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        //old filter for when category was not json
        //return $query
        return $query->where('category', 'LIKE', '%'.$value.'%');
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
             "Дома из профилированного бруса" => "df_cat_1",
             "Бани из клееного бруса" => "df_cat_2",
             "Дома из блоков" => "df_cat_3",
             "Дома из оцилиндрованного бревна" => "df_cat_4",
             "Бани из бруса камерной сушки" => "df_cat_5",
             "Бани из бруса сосна/ель" => "df_cat_6",
             "Бани из оцилиндрованного бревна" => "df_cat_7",
             "Дом-баня из бруса" => "df_cat_8",
             "Дома из бруса камерной сушки" => "df_cat_9",
             "Дома из клееного бруса" => "df_cat_10",
             "Бани с бассейном" => "df_cat_11",
             "Каркасные дома" => "df_cat_12",
             "Бани из бревна кедра" => "df_cat_13",
             "Бани из бревна лиственницы" => "df_cat_14",
             "Бани из бруса кедра" => "df_cat_15",
             "Бани из бруса лиственницы" => "df_cat_16",
             "Дачные дома" => "df_cat_17",
             "Дом-баня из бревна" => "df_cat_18",
             "Дома из бревна кедра" => "df_cat_19",
             "Дома из бревна лиственницы" => "df_cat_20",
             "Дома из бруса кедра" => "df_cat_21",
             "Дома из бруса лиственницы" => "df_cat_22"
        ];
    }
}
