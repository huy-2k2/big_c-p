@extends('layouts.agent')
@section('content')
  <div class="max-w-[100vw]">
    <form action="{{ route('agent.confirm_sell_to_customer') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-6 p-5 rounded-lg shadow-lg">
        @csrf
        @include('components.heading', ['text' => 'Thông tin chi tiết'])
        <input type="text" class="hidden" name="user_id_to" value="{{ $customer->id }}">
        <input type="text" class="hidden" name="product_id" value="{{ $product->id }}">
        @include('components.input_float_disable', ['value' => $customer->id, 'label' => 'id người dùng'])
        @include('components.input_float_disable', ['value' => $customer->name, 'label' => 'tên người dùng'])
        @include('components.input_float_disable', ['value' => $customer->email, 'label' => 'email người dùng'])
        @include('components.input_float_disable', ['value' => $address_customer->province. ', ' . $address_customer->district . ', ' . $address_customer->sub_district, 'label' => 'địa chỉ'])
        @include('components.input_float_disable', ['value' => $product->id, 'label' => 'id sản phẩm'])
        @include('components.input_float_disable', ['value' => $range_name, 'label' => 'dòng sản phẩm'])
        @include('components.button_submit', ['text' => 'xác nhận đơn hàng'])
        <a href="{{route('agent.sell_to_customer')}}" class="text-right text-red-600 underline">Hủy đơn hàng</a>
    </form>
</div>
@endsection