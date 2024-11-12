@extends('layouts.default')

@section('content')
<div class="container-xxl mt-6 mb-6">
@foreach ($invoices as $data)
    @foreach ($data as $groupName => $sections)
            <div class="card">
                <div class="card-header">
                    Строительно-монтажные работы {{ $groupName }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Наименование работ</th>
                                <th>Ед.Изм.</th>
                                <th>Количество</th>
                                <th>Цена работ, руб.</th>
                                <th>Итого работы, руб.</th>
                                <th>Наименование материала</th>
                                <th>Ед.Изм.</th>
                                <th>Количество</th>
                                <th>Цена работ, руб.</th>
                                <th>Итого работы, руб.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sections as $sectionNumber => $sides)
                                <tr><td colspan="10"><b> {{ $sectionNumber }} </b></td></tr>
                                @php
                                    $maxCount = max(count($sides['L']), count($sides['R']));
                                @endphp
                                @for ($i = 0; $i < $maxCount; $i++)
                                    <tr>
                                        <!-- Handle 'L' items -->
                                        @if (array_key_exists($i, $sides['L']))
                                            <td>{{ $sides['L'][$i]['title'] }}</td>
                                            <td>{{ $sides['L'][$i]['unit'] }}</td>
                                            <td>{{ $sides['L'][$i]['quantity'] }}</td>
                                            <td>{{ $sides['L'][$i]['cost'] }}</td>
                                            <td>{{ $sides['L'][$i]['total'] }}</td>
                                        @else
                                            <td colspan="5"></td>
                                        @endif

                                        <!-- Handle 'R' items -->
                                        @if (array_key_exists($i, $sides['R']))
                                            <td>{{ $sides['R'][$i]['title'] }}</td>
                                            <td>{{ $sides['R'][$i]['unit'] }}</td>
                                            <td>{{ $sides['R'][$i]['quantity'] }}</td>
                                            <td>{{ $sides['R'][$i]['cost'] }}</td>
                                            <td>{{ $sides['R'][$i]['total'] }}</td>
                                        @else
                                            <td colspan="5"></td>
                                        @endif
                                    </tr>
                                @endfor
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <th>Итого:</th>
                                <th>0.00</th>
                                <td colspan="3"></td>
                                <th>Материалы:</th>
                                <th>0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer text-muted">
                    Invoice Date: {{ date('Y-m-d') }}
                </div>
            </div>
    @endforeach
    @endforeach
</div>
@endsection
