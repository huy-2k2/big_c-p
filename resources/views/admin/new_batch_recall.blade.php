@extends('layouts.admin')
@section('content')
@php
    $options = []
@endphp
@foreach ($batches as $batch)
    @php
        $options[] = [
          'value' => $batch->id,
          'title' => $batch->factory->user->name .', '. $batch->range->name.', '. $batch->manufacturing_date
        ]
    @endphp
@endforeach
<div class="max-w-[100vw]">
  <form action="{{ route('admin.post_new_batch_recall') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
      @csrf
      @include('components.input_select', ['name' => 'batch_id', 'label' => 'chọn lô hàng', 'options' => $options])
      @include('components.button_submit', ['text' => 'Thu hồi lô hàng'])
  </form>
</div>
@endsection     