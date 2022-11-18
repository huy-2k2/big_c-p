@extends('layouts.auth')

@section('content')
    <form action={{ route('login') }} method="POST" class="w-[400px] max-w-full p-5 border rounded-lg shadow-md flex flex-col gap-y-5">
        @csrf
        @include('components.input', ['name' => 'email', 'label' => 'nhập email'])
          
        @include('components.input', ['type' => 'password', 'name' => 'password', 'label' => 'nhập mật khẩu'])
           
        @include('components.button_submit', ['text' => 'Đăng nhập'])
        <div class="flex flex-wrap items-center justify-between">
            @include('components.input_checkbox', ['name' => 'remember', 'label' => 'nhớ mật khẩu'])
            @if (Route::has('password.request'))
               @include('components.link', ['href' => route('password.request'), 'text' => 'quên mật khẩu?'])
            @endif
        </div>
    </form>
@endsection
