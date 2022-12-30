@extends('layouts.factory')
@section('content')
        <div class="max-w-[100vw]">
            <form action="{{ route('factory.print_statistic_defective') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
                @csrf
                @include('components.input_checkbox', ['name' => 'ranges', 'label' => 'chọn theo dòng sản phẩm' ])
                @include('components.input_checkbox', ['name' => 'factories', 'label' => 'chọn theo cơ sở sản xuất' ])
                @include('components.input_checkbox', ['name' => 'agents', 'label' => 'chọn theo đại lý phân phối' ])
                @include('components.button_submit', ['text' => 'in thống kê'])
            </form>
        </div>
    @parent
@endsection     