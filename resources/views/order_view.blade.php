@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    @include('partials.tab-navigator', ['items' => [
        ['url' => '/my-orders', 'label' => 'Заказы'],
        ['url' => '/suppliers', 'label' => 'Строители'],
        ['url' => '/messages', 'label' => 'Мои переписки'],
        ['url' => '/profile', 'label' => 'Мои данные'],
    ]])
    @php
    
    $smetaTotal = formatPrice(($displayLabour ? $totals['labour'] * 1.19 : 0) + $totals['material'] * 1.035 + $totals['shipping']);


    function formatPrice($number, $displayLabour = true) {
        if (!$displayLabour) {
            return '0.00';
        }
        // Convert to float if it's a string
        $number = is_string($number) ? (float)$number : $number;
        
        // Format the number
        $formatted = number_format($number, 2, '.', ' ');
        return str_replace(',', ' ', $formatted);
    }
@endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Детали заказа - {{ $project->human_ref }}</h4>
                </div>
                <div class="card-body">
                    <h4 class="text-dark mb-4">Общая информация</h4>
                    <div class="row mb-2">
                        <div class="col-3">
                            <h5 class="f-w-500">Проект <span class="pull-right">:</span></h5>
                        </div>
                        <div class="col-9"><span>{{ $designTitle }}</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-3">
                            <h5 class="f-w-500">Дата создания <span class="pull-right">:</span></h5>
                        </div>
                        <div class="col-9"><span>{{ $project->created_at->timezone('Europe/Moscow')->format('d.m.Y H:i') }}</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-3">
                            <h5 class="f-w-500">Общая стоимость <span class="pull-right">:</span></h5>
                        </div>
                        <div class="col-9"><span>{{ $smetaTotal }} руб.</span></div>
                    </div>

                    @foreach($sheetStructures as $sheetStructure)
                        <h4 class="text-dark mt-5 mb-4">{{ $sheetStructure['title'] }}</h4>
                        @php
                            $invoiceTotalLabour = 0;
                            $invoiceTotalMaterial = 0;
                        @endphp
                        @foreach($sheetStructure['data']['sections'] as $section)
                            @if($section['sectionTotalLabour'] > 0 || $section['sectionTotalMaterial'] > 0)
                                <h5 class="mt-4">{{ $section['value'] }}</h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="6" style="width: 50%;">Работы</th>
                                                @if (!Str::contains(strtolower($section['value']), 'Транспортные'))
                                                    <th colspan="5" style="width: 50%;">Материалы</th>
                                                @else
                                                    <th colspan="5" style="width: 50%;">Транспорт</th>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th style="width: 3%;">№</th>
                                                <th style="width: 20%;">Наименование работ</th>
                                                <th style="width: 4%;">Ед.</th>
                                                <th style="width: 7%;">Кол-во</th>
                                                <th style="width: 8%;">Цена</th>
                                                <th style="width: 8%;">Сумма</th>
                                                <th style="width: 20%;">Наименование материалов</th>
                                                <th style="width: 4%;">Ед.</th>
                                                <th style="width: 7%;">Кол-во</th>
                                                <th style="width: 8%;">Цена</th>
                                                <th style="width: 8%;">Сумма</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $maxRows = max(count($section['labourItems']), count($section['materialItems']));
                                                $sectionTotalLabour = $displayLabour ? $section['sectionTotalLabour'] : 0;
                                            @endphp
                                            @for ($i = 0; $i < $maxRows; $i++)
                                            @if ((isset($section['labourItems'][$i]) && $section['labourItems'][$i]['labourQuantity'] > 0) || (isset($section['materialItems'][$i]) && $section['materialItems'][$i]['materialQuantity'] > 0))
                                                <tr>
                                                    @if (isset($section['labourItems'][$i]) && $section['labourItems'][$i]['labourQuantity'] > 0)
                                                        <td>{{ $section['labourItems'][$i]['labourNumber'] ?? '' }}</td>
                                                        <td>{{ $section['labourItems'][$i]['labourTitle'] ?? '' }}</td>
                                                        <td>{{ $section['labourItems'][$i]['labourUnit'] ?? '' }}</td>
                                                        <td>{{ $section['labourItems'][$i]['labourQuantity'] ?? '' }}</td>
                                                        <td>{{ formatPrice($section['labourItems'][$i]['labourPrice'] ?? 0, $displayLabour) }}</td>
                                                        <td>{{ formatPrice($section['labourItems'][$i]['labourTotal'] ?? 0, $displayLabour) }}</td>
                                                    @else
                                                        <td colspan="6"></td>
                                                    @endif

                                                    @if (isset($section['materialItems'][$i]) && $section['materialItems'][$i]['materialQuantity'] > 0)
                                                        <td>{{ $section['materialItems'][$i]['materialTitle'] ?? '' }}</td>
                                                        <td>{{ $section['materialItems'][$i]['materialUnit'] ?? '' }}</td>
                                                        <td>{{ $section['materialItems'][$i]['materialQuantity'] ?? '' }}</td>
                                                        <td>{{ formatPrice($section['materialItems'][$i]['materialPrice'] ?? 0) }}</td>
                                                        <td>{{ formatPrice($section['materialItems'][$i]['materialTotal'] ?? 0) }}</td>
                                                    @else
                                                        <td colspan="5"></td>
                                                    @endif
                                                    </tr>
                                                @endif
                                            @endfor
                                            <tr>
                                                <td colspan="3"></td>
                                                <td colspan="3" align="right">Итого работы: {{ formatPrice($sectionTotalLabour) }} руб.</td>
                                                <td colspan="2"></td>
                                                <td colspan="3" align="right">
                                                    @if (Str::contains(strtolower($section['value']), 'Транспортные'))
                                                        Итого: {{ formatPrice($section['sectionTotalMaterial']) }} руб.
                                                    @else
                                                        Итого материалы: {{ formatPrice($section['sectionTotalMaterial']) }} руб.
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                @php
                                    $invoiceTotalLabour += $sectionTotalLabour;
                                    $invoiceTotalMaterial += $section['sectionTotalMaterial'];
                                @endphp
                            @endif
                        @endforeach

                        @php
                            $invoiceTotalLabour = $displayLabour ? $invoiceTotalLabour : 0;
                        @endphp

                        <h5 class="mt-4 text-dark text-center">Итого по разделу</h5>
                        <p class="font-weight-bold text-center">
                            Работы: {{ formatPrice($invoiceTotalLabour) }} руб.<br>
                            Материалы: {{ formatPrice($invoiceTotalMaterial) }} руб.
                        </p>
                    @endforeach

                    <h4 class="text-dark mt-5 mb-4 text-center">Итого по смете</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width: 50%; margin: 0 auto;">
                            <tbody>
                                <tr>
                                    <td style="width: 70%;">Стоимость Работ</td>
                                    <td style="width: 30%;" class="text-right">{{ formatPrice($totals['labour'], $displayLabour) }} руб.</td>
                                </tr>
                                <tr>
                                    <td>Транспортные расходы - 3%</td>
                                    <td class="text-right">{{ formatPrice($totals['labour'] * 0.03, $displayLabour) }} руб.</td>
                                </tr>
                                <tr>
                                    <td>Накладные расходы - 16%</td>
                                    <td class="text-right">{{ formatPrice($totals['labour'] * 0.16, $displayLabour) }} руб.</td>
                                </tr>
                                <tr>
                                    <td><strong>Итого Работы</strong></td>
                                    <td class="text-right"><strong>{{ formatPrice($totals['labour'] * 1.19, $displayLabour) }} руб.</strong></td>
                                </tr>
                                <tr>
                                    <td>Стоимость материалов</td>
                                    <td class="text-right">{{ formatPrice($totals['material']) }} руб.</td>
                                </tr>
                                <tr>
                                    <td>Непередвиденные - 3,5%</td>
                                    <td class="text-right">{{ formatPrice($totals['material'] * 0.035) }} руб.</td>
                                </tr>
                                <tr>
                                    <td>Транспортные расходы</td>
                                    <td class="text-right">{{ formatPrice($totals['shipping']) }} руб.</td>
                                </tr>
                                <tr>
                                    <td><strong>Итого Материалы</strong></td>
                                    <td class="text-right"><strong>{{ formatPrice($totals['material'] * 1.035 + $totals['shipping']) }} руб.</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>ИТОГО по смете, руб</strong></td>
                                    <td class="text-right"><strong>{{ $smetaTotal }} руб.</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection