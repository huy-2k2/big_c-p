@extends('layouts.admin')
@section('content')
<div class="max-w-[100vw]">
    <form action="{{ route('admin.store_product_line') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
        @csrf
        @include('components.input', ['name' => 'name', 'label' => 'tên dòng sản phẩm'])
        @include('components.textarea', ['name' => 'property', 'label' => 'thuộc tính dòng sản phẩm'])
        @include('components.input', ['name' => 'warranty_time', 'label' => 'số tháng bảo hành'])
        @include('components.button_submit', ['text' => 'thêm dòng sản phẩm'])
    </form>
</div>
@endsection