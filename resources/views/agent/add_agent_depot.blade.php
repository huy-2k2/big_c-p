@extends('layouts.agent')
@section('content')
<div class="max-w-[100vw]">
    <form action="{{ route('agent.post_add_agent_depot') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
        @csrf
        @include('components.input', ['name' => 'depot_name', 'label' => 'tên kho'])
        @include('components.input', ['name' => 'size', 'label' => 'kích cỡ'])
        @include('components.button_submit', ['text' => 'Tạo kho mới'])
    </form>
</div>
@endsection