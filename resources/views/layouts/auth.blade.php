@extends('layouts.app')
@section('app')
<nav class="flex items-center justify-between px-4 py-2">
    <a href={{ route('home') }} class="flex items-center justify-center">
        <img src={{ url('images/logo.jpg') }} class="object-cover w-20" alt="Big corp Logo" />
        <span class="self-center hidden text-2xl font-bold uppercase sm:inline-block text-primary whitespace-nowrap">Big Corp</span>
    </a>
    <div class="flex items-center justify-center">
      <a href={{ route('login') }}>
        @include('components.button', ['is_active' => (request()->is('login')), 'text' => 'Đăng nhập'])
      </a>
      <a href={{ route('register') }}>
        @include('components.button', ['is_active' => (request()->is('register')), 'text' => 'Đăng Ký'])
      </a>
      
    </div>
</nav>
<div class="flex items-center justify-center mt-[100px] content p-5">
  @yield('content')
</div>
@endsection