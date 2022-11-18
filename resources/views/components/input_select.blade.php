<div class="flex flex-col gap-y-1">
    <div class="select-wrapper">
        <select id='input-select-{{ $name }}' class="block w-full px-3 text-sm text-black bg-white border border-gray-300 rounded-lg appearance-none peer did-floating-select h-11 focus:border-blue-600 focus:outline-none" onclick="this.setAttribute('value', this.value);" onchange="this.setAttribute('value', this.value);" value=""  name={{ $name }}>
            <option class="hidden"></option>
            @foreach ($options as $option)
                <option value={{ $option['value'] }}>{{ $option['title'] }}</option>
            @endforeach
        </select>
        <label class="absolute px-1 text-sm text-gray-500 duration-300 bg-white pointer-events-none did-floating-label left-2 top-[11px]">{{ $label }}</label>
        <span class="absolute w-5 text-gray-500 duration-300 -translate-y-1/2 peer-focus:rotate-180 right-2 top-1/2">
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Chevron Down</title><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M112 184l144 144 144-144"/></svg>
        </span>
    </div>
    @error($name)
        @include('components.text_notice', ['text' => $message])
    @enderror
</div>

@if(old($name))
    <script>
        const input_select_{{ $name }} = document.querySelector('#input-select-{{ $name }}')
        input_select_{{ $name }}.querySelectorAll('option').forEach(option => {
        if(option.value == "{{ old($name) }}") {
            option.setAttribute('selected', 'selected')
            input_select_{{ $name }}.setAttribute('value', '{{ old($name) }}')            
        }
      });
    </script>
@endif