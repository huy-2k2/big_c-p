@extends('layouts.auth')

@section('content')
    <form action={{ route('register') }} method="POST" class="w-[400px] max-w-full p-5 border rounded-lg shadow-md flex flex-col gap-y-5">
        @csrf
        @include('components.input', ['name' => 'name', 'label' => 'nhập tên đầy đủ'])
        
        @include('components.input', ['name' => 'email', 'label' => 'nhập email'])
        
        @include('components.input', ['type' => 'password', 'name' => 'password', 'label' => 'nhập mật khẩu'])
           
        @include('components.input', ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'nhập lại mật khẩu'])
       
        @include('components.input_select', ['name' => 'role_id', 'label' => 'chọn vai trò', 'options' => [['title'=> 'nhà máy sản xuất', 'value' => 2 ], ['title' => 'trung tâm bảo hành', 'value' => 3], ['title' => 'đại lý bán hàng', 'value' => 4]]])
        
        @include('components.inputs_address')
        
        @include('components.button_submit', ['text' => 'Đăng ký'])
    </form>
@endsection
