<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as DBS;

class InvoiceSectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = '
        [
            {
              "parent_invoice_structure": "dGlue145160",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145160",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145160",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145160",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145160",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145160",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145240",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145240",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145240",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145240",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145240",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue145240",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190160",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190160",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190160",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190160",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190160",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190160",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190240",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190240",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190240",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190240",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190240",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue190240",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230160",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230160",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230160",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230160",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230160",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230160",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230240",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230240",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230240",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230240",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230240",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue230240",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250160",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250160",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250160",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250160",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250160",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250160",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue250200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue280200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue280200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue280200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue280200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue280200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dGlue280200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145",
              "section": 3,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145",
              "section": 4,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145195",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145195",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145195",
              "section": 3,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera145195",
              "section": 4,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14545",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж комплекта",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14545",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14545",
              "section": 3,
              "visibility": "",
              "title": "2. Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14545",
              "section": 4,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14595",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14595",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14595",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14595",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж потолочного перекрытия",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera14595",
              "section": 5,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera195",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera195",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera195",
              "section": 3,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dKamera195",
              "section": 4,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145140",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145140",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145140",
              "section": 3,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145140",
              "section": 4,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145190",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145190",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145190",
              "section": 3,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus145190",
              "section": 4,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus195190",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus195190",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus195190",
              "section": 3,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrus195190",
              "section": 4,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145140",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145140",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145140",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145140",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145140",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145140",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145190",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145190",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145190",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145190",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145190",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK145190",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK195190",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK195190",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK195190",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK195190",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK195190",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusK195190",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145140",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145140",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145140",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145140",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145140",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145140",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145190",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145190",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145190",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145190",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145190",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL145190",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL195190",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL195190",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL195190",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL195190",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL195190",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dNaturalBrusL195190",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr220",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr220",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr220",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr220",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr220",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr220",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr240",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr240",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr240",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr240",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr240",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr240",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr260",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr260",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr260",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr260",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr260",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr260",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr280",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr280",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr280",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr280",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr280",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr280",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr300",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr300",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr300",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr300",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr300",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBKedr300",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList220",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList220",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList220",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList220",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList220",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList220",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList240",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList240",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList240",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList240",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList240",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList240",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList260",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList260",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList260",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList260",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList260",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList260",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList280",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList280",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList280",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList280",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList280",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList280",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList300",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList300",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList300",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList300",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList300",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBList300",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos180",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos180",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos180",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos180",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos180",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos180",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos200",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos200",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos200",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos200",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos200",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos200",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos220",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos220",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos220",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos220",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos220",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos220",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos240",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos240",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos240",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos240",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos240",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos240",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos260",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos260",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos260",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos260",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos260",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos260",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos280",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos280",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos280",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos280",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos280",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos280",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos300",
              "section": 1,
              "visibility": "",
              "title": "1.  Монтаж стен ",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos300",
              "section": 2,
              "visibility": "",
              "title": "2. Монтаж перекрытия первого этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos300",
              "section": 3,
              "visibility": "",
              "title": "2.1 Монтаж перекрытия второго этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos300",
              "section": 4,
              "visibility": "",
              "title": "2.2 Монтаж перекрытия третьего этажа",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos300",
              "section": 5,
              "visibility": "",
              "title": "2.3 Монтаж перекрытия потолка",
              "params": ""
            },
            {
              "parent_invoice_structure": "dOCBSos300",
              "section": 6,
              "visibility": "",
              "title": "  Прием материалов. Транспортные расходы",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalP",
              "section": 1,
              "visibility": "",
              "title": "1. Стропильная система",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalP",
              "section": 2,
              "visibility": "",
              "title": "2. Устройство основания кровли ",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalP",
              "section": 3,
              "visibility": "",
              "title": "3. Монтаж кровельного покрытия",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalP",
              "section": 4,
              "visibility": "",
              "title": "  Водосток пластиковый",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftP",
              "section": 1,
              "visibility": "",
              "title": "1. Стропильная система",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftP",
              "section": 2,
              "visibility": "",
              "title": "2. Устройство основания кровли ",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftP",
              "section": 3,
              "visibility": "",
              "title": "3. Монтаж кровельного покрытия",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftP",
              "section": 4,
              "visibility": "",
              "title": "  Водосток пластиковый",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalM",
              "section": 1,
              "visibility": "",
              "title": "1. Стропильная система",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalM",
              "section": 2,
              "visibility": "",
              "title": "2. Устройство основания кровли ",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalM",
              "section": 3,
              "visibility": "",
              "title": "3. Монтаж кровельного покрытия",
              "params": ""
            },
            {
              "parent_invoice_structure": "rMetalM",
              "section": 4,
              "visibility": "",
              "title": " Водосток металлический",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftM",
              "section": 1,
              "visibility": "",
              "title": "1. Стропильная система",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftM",
              "section": 2,
              "visibility": "",
              "title": "2. Устройство основания кровли ",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftM",
              "section": 3,
              "visibility": "",
              "title": "3. Монтаж кровельного покрытия",
              "params": ""
            },
            {
              "parent_invoice_structure": "rSoftM",
              "section": 4,
              "visibility": "",
              "title": " Водосток металлический",
              "params": ""
            }
           ]
        ';

    $array = json_decode($sections);
       
   $DBM = new DBS;
   foreach ($array as $item) {
       $DBM::table('invoice_sections')->insert([
           'parent_invoice_structure' => $item->parent_invoice_structure,
           'visibility' => $item->title,
           'section' => $item->section
         ]); 
    }
}}
