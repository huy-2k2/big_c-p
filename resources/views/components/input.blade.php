@php
    $invalid_name = str_replace('-', '_', $name);    
@endphp
<div class="flex flex-col gap-y-1">
    <div class="relative w-full" id="input-field-{{ $name }}">
        <input autocomplete="off" type={{ isset($type)? $type: 'text' }} id={{ $name }} name={{ $name }} class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " value="{{$default_value ?? old($name) }}" />
        @if (isset($type) && $type=='password')
            <span class="absolute top-0 right-0 flex items-center justify-center w-12 h-full bg-white border border-gray-300 rounded-tr-lg rounded-br-lg cursor-pointer select-none border-l-transparent peer-focus:border-blue-600 peer-focus:border-l-transparent">hiện</span>
        @endif
        <label for={{ $name }} class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-0 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">{{ $label }}</label>
    </div>
    @error($name)
        @include('components.text_notice', ['text' => $message])
    @enderror
    
</div>

@if (isset($type) && $type == 'password')    
    <script>
        (() => {
            const toggle_type_btn_{{ $invalid_name }} = document.querySelector('#input-field-{{ $name }} span')
            const input_{{ $invalid_name }} = document.querySelector('#input-field-{{ $name }} input')
            toggle_type_btn_{{ $invalid_name }}.onclick = function() {
                if(input_{{ $invalid_name }}.type == 'password') {
                    input_{{ $invalid_name }}.type = 'text'
                    toggle_type_btn_{{ $invalid_name }}.innerText = 'ẩn'
                } else {
                    input_{{ $invalid_name }}.type = 'password'
                    toggle_type_btn_{{ $invalid_name }}.innerText = 'hiện'
                }
            }
        })()
    </script>
@endif