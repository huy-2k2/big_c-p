@extends('layouts.customer')
@section('content')
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Id sản phẩm</th>
        <th scope="col">Dòng sản phẩm</th>
        <th scope="col">Sản xuất tại</th>
        <th scope="col">Mua tại</th>
        <th scope="col">Số lần bảo hành</th>
        <th scope="col">Thời gian mua</th>
        <th scope="col">Hạn bảo hành</th>
        <th scope="col">Trạng thái</th>
        <th scope="col">Chức năng</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product) 
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ (DB::table('ranges')->where('id', $product->range_id)->first())->name }}</td>
            <td>{{ (DB::table('users')->where('id', $product->factory_id)->first())->name }}</td>
            <td>{{ (DB::table('users')->where('id', $product->agent_id)->first())->name }}</td>
            <td>{{ $product->warranty_count }}</td>
            <td>{{ $product->customer_buy_time }}</td>
            <td>{{ $product->end_date }}</td>
            <td>
              @if($product->out_of_warranty == 1) 
                Hết hạn bảo hành
               @else
                Đang ở {{ (DB::table('statuses')->where('id', $product->status_id)->first())->name }}
              @endif
            </td>
            <td>
              @if($product->status_id == 3 && $product->out_of_warranty == 0) 
                <button class="btn btn-danger">
                  <a href = '{{ route('customer.warranty_claim', ['id' => $product->id]) }}'>Yêu cầu bảo hành</a>
                </button>
              @endif
            </td>
        </tr>
      
      @endforeach
    </tbody>
  </table>
@endsection