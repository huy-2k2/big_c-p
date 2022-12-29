@extends('layouts.agent')
@section('content')
<div class="max-w-[100vw]">
  <form action="{{ route('agent.check_sell_to_customer') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
      @csrf
      @include('components.input', ['name' => 'customer_email', 'label' => 'email người dùng'])
      @include('components.input', ['name' => 'product_id', 'label' => 'id sản phẩm'])
      @include('components.button_submit', ['text' => 'kiểm tra'])
  </form>
</div>
@endsection