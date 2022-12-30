@extends('layouts.factory')
@section('content')
@php
    $range_options = [];
    $agent_options = [];
@endphp
@foreach ($ranges as $range)
    @php
        $range_options[] = [
          'value' => $range->id,
          'title' => $range->name
        ]
    @endphp
@endforeach
@foreach ($agents as $agent)
    @php
        $agent_options[] = [
          'value' => $agent->user_id,
          'title' => $agent->user->name
        ]
    @endphp
@endforeach
<div class="max-w-[100vw]">
  <form action="{{ route('factory.post_transfer_prod_to_agent') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
      @csrf
      
      @include('components.input', ['name' => 'quantity_prod', 'label' => 'số lượng'])
      @include('components.input_select', ['name' => 'range', 'label' => 'dòng sản phẩm', 'options' => $range_options])
      @include('components.input_select', ['name' => 'agent', 'label' => 'đại lý', 'options' => $agent_options])
      @include('components.button_submit', ['text' => 'xuất kho'])
  </form>
</div>
@endsection