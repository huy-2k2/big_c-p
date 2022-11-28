@php
    $btn_close_class = $btn_close_class ?? '';    
@endphp
<span @isset($data_index)data-index='{{$data_index }}' @endisset id="{{ $id }}" class="{{ $btn_close_class }}  absolute top-0 right-0 z-50 flex items-center justify-center h-10 p-4 text-white bg-red-600 cursor-pointer w-14">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>  
</span>