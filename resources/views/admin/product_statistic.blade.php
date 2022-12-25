@extends('layouts.admin')
@section('content')
        <div class="max-w-[100vw]">
            <form action="{{ route('admin.print_statistic') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
                @csrf
                @include('components.input_select_advance', ['name' => 'status', 'label' => 'chọn trạng thái', 'options' => $statuses])
                @include('components.input_select_user', ['name' => 'agent', 'label' => 'chọn đại lý', 'users' => $vendors])
                @include('components.input_select_user', ['name' => 'factory', 'label' => 'chọn cơ sở xản xuất', 'users' => $factories])
                @include('components.input_select_user', ['name' => 'warranty', 'label' => 'chọn trung tâm bảo hành', 'users' => $warranty_centers])
                @include('components.button_submit', ['text' => 'in thống kê'])
            </form>
        </div>
    @parent
@endsection     