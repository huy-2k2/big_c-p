@extends('layouts.factory')
@section('content')
  @php
      $range_options = [];
      $depot_options = [];
  @endphp
  @foreach ($ranges as $range)
      @php
          $range_options[] = [
            'value' => $range->id,
            'title' => $range->name
          ]
      @endphp
  @endforeach
  @foreach ($depots as $depot)
      @php
          $depot_options[] = [
            'value' => $depot->id,
            'title' => $depot->depot_name
          ]
      @endphp
  @endforeach
  <div class="max-w-[100vw]">
    <form action="{{ route('factory.create_batch_post') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
        @csrf
        @include('components.input_select', ['name' => 'range', 'label' => 'chọn dòng sản phẩm', 'options' => $range_options])
        @include('components.input_select', ['name' => 'depot', 'label' => 'chọn kho chứa', 'options' => $depot_options])
        @include('components.input', ['name' => 'quantity', 'label' => 'chọn số lượng'])
        @include('components.button_submit', ['text' => 'Tạo lô mới'])
    </form>
</div>
@endsection