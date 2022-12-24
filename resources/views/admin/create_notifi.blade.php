@extends('layouts.admin')
@section('content')
        <div class="max-w-[100vw]">
            <form action="{{ route('admin.store_notifi') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
                @csrf
                @include('components.input', ['name' => 'title', 'label' => 'tiêu đề thông báo'])
                @include('components.textarea', ['name' => 'content', 'label' => 'nội dung thông báo'])
                @include('components.input_select_user', ['name' => 'vendor', 'label' => 'chọn đại lý', 'users' => $vendors])
                @include('components.input_select_user', ['name' => 'factory', 'label' => 'chọn cơ sở xản xuất', 'users' => $factories])
                @include('components.input_select_user', ['name' => 'warranty_center', 'label' => 'chọn trung tâm bảo hành', 'users' => $warranty_centers])
                @include('components.button_submit', ['text' => 'gửi thông báo'])
            </form>
        </div>
    @parent
@endsection     