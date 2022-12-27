@extends('layouts.customer')
@section('content')
<form class="form-control" method="POST" action='{{ route('customer.send_warranty_claim') }}'>
  @csrf
  <ol>
    <input style="display:none"  name="product_id" value="{{ $product->id }}">
    <li>Id: sản phẩm: {{ $product->id }}</li>
    <li>Dòng sản phẩm: {{ (DB::table('ranges')->where('id', $product->range_id)->first())->name }}</li>
    <li>Nhà máy sản xuất: {{ (DB::table('users')->where('id', $product->factory_id)->first())->name }}</li>
    <li>Mua từ đại lý: {{ (DB::table('users')->where('id', $product->agent_id)->first())->name }}</li>
    <li>Số lần bảo hành: {{ $product->warranty_count }}</li>
    <li>Thời gian mua hàng: {{ $product->customer_buy_time }}</li>
    <li>Trạng thái: Đang ở {{ (DB::table('statuses')->where('id', $product->status_id)->first())->name }}</li>
    <br>
    <li><label for="claim_reason">Lý do bảo hành: </label></li>
    <li><input class="border border-info" type="text" name="claim_reason" id="claim_reason" placeholder="Điền lý do yêu cầu"></li>
    <li>
      <button type="submit" class="mt-10 btn btn-danger">
        Yêu cầu bảo hành
      </button>
    </li>
  </ol>
</form>

@endsection