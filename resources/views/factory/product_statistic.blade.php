@extends('layouts.factory')
@section('content')
        <div class="max-w-[100vw]">
            <form action="{{ route('factory.print_statistic') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
                @csrf
                @include('components.input_select_advance', ['name' => 'status', 'label' => 'chọn trạng thái', 'options' => $statuses])
                
                @include('components.input_select_advance', ['name' => 'months', 'label' => 'chọn tháng',
                'options' => [['id'=>1, 'name'=>1], ['id'=> 2, 'name' => 2], ['id' => 3, 'name' => 3], 
                ['id' => 4, 'name' => 4], ['id' => 5, 'name' => 5], ['id' => 6, 'name' => 6], 
                ['id' => 7, 'name' => 7], ['id' => 8, 'name' => 8], ['id' => 9, 'name' => 9],
                ['id' => 10, 'name' => 10], ['id' => 11, 'name' => 11], ['id' => 12, 'name' => 12] ]])
                @include('components.input_select_advance', ['name' => 'quarter', 'label' => 'chọn quý', 
                'options' => [['id' => 1, 'name' => 'I'], ['id' => 2, 'name' => 'II'], ['id' => 3, 'name' => 'III'], 
                ['id' => 4, 'name' => 'IV']] ])
                @include('components.input_select_advance', ['name' => 'year', 'label' => 'chọn năm', 
                'options' => [['id' => 2018, 'name' => 2018], ['id' => 2019, 'name' => 2019],
                ['id' => 2020, 'name' => 2020], ['id' => 2021, 'name' => 2021],
                ['id' => 2022, 'name' => 2022]]])
                @include('components.button_submit', ['text' => 'in thống kê'])
            </form>
        </div>
    @parent
@endsection     