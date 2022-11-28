<div class="flex flex-col gap-y-1">
    <div class="relative w-full" id="input-field-{{ $name }}">
        <textarea autocomplete="off" name={{ $name }} id={{ $name }} class="h-[150px] resize-none custom-scrollbar block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" ">{{ old($name) }}</textarea>    
        <label for={{ $name }} class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-0 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-5 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">{{ $label }}</label>
    </div>
    @error($name)
        @include('components.text_notice', ['text' => $message])
    @enderror
</div>