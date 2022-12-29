@extends('layouts.customer')
@section('content')
  @php
      $tbody = []
  @endphp
  @foreach ($products as $product)
      @php
          $a = ''
      @endphp
      @if($product->status_id == 3 && $product->out_of_warranty == 0) 
          @php
              $href = route('customer.warranty_claim', ['id' => $product->id]);
              $a = "<a class='text-blue-600 hover:underline' href ='$href'>Yêu cầu bảo hành</a>";
          @endphp  
      @endif
      @php
          $tbody[] = [
            $product->id,
            (DB::table('ranges')->where('id', $product->range_id)->first())->name,
            (DB::table('users')->where('id', $product->factory_id)->first())->name,
            (DB::table('users')->where('id', $product->agent_id)->first())->name,
            $product->warranty_count,
            $product->customer_buy_time,
            $product->end_date,
            $a,
          ]
      @endphp
  @endforeach
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
    @include('components.table', ['title' => 'Bảo hành', 'ths' => ['id sản phẩm',  'dòng sảm phẩm', 'sản xuất tại','mua tại', 'số lần bảo hành', 'thời gian mua', 'hạn bảo hành', 'trạng thái', ['title' => 'yêu cầu bảo hành', 'sr_only' => true]], 'tbody' => $tbody])
  </div>
@endsection