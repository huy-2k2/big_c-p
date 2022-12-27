@extends('layouts.agent')
@section('content')
  <form method="POST" action='{{ route('agent.confirm_sell_to_customer') }}'>
    @csrf
    <div class="row">
      <div class="card" style="width: 18rem;">
        <input style="display:none" name="user_id_to" value="{{ $customer->id }}">
        <img class="card-img-top" src="..." alt="Card image cap">
        <div class="card-body">
          <h4 class="card-title">Thông tin người dùng</h4>
          <ol>
            <li>Id người dùng: {{ $customer-> id}}</li>
            <li>Tên: {{ $customer-> name}}</li>
            <li>Email: {{ $customer-> email}}</li>
            <li>Địa chỉ: {{ 'Xã '.$address_customer->sub_district.', Huyện '.$address_customer->district.', Tỉnh '.$address_customer->province}}</li>
          </ol>
        </div>
      </div>
      <div class="card" style="width: 18rem;">
        <input style="display:none" name="product_id" value="{{ $product->id }}">
        <img class="card-img-top" src="..." alt="Card image cap">
        <div class="card-body">
          <h4 class="card-title">Thông tin sản phẩm</h4>
          <ol>
            <li>Id sản phẩm: {{ $product->id }}</li>
            <li>Dòng sản phẩm: {{ $range_name }}</li>
          </ol>
        </div>
      </div>
    </div>
    
    
    <button type="submit" class="btn btn-primary">Xác nhận</button>
    <button class="btn btn-danger">
      <a href = '{{ route('agent.sell_to_customer') }}'>Chỉnh sửa</a>
    </button>
  </form>
@endsection