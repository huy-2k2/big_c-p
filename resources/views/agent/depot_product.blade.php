@extends('layouts.agent')
@section('content')
@php
    $tbody = []
@endphp
@foreach ($results as $item)
    @php
        $tbody[] = [
          $item['depot']->depot_name,
          $item['range']->name,
          $item['quantity'],
          $item['available']. '/ '. $item['depot']->size
        ]
    @endphp
@endforeach
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  @include('components.table', ['title' => 'số lượng sản phẩm', 'ths' => ['tên kho',  'tên dòng sản phẩm', 'số lượng','số lượng kho trống'], 'tbody' => $tbody])
</div>
@endsection