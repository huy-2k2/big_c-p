@php
    $select_dependents = []    
@endphp
<div class="relative">
    <div id="select-{{ $name }}-inputs" class="hidden"></div>
 
    <ul id="select-{{ $name }}-list" class="max-h-[150px] overflow-y-auto custom-scrollbar bg-white select-user-list hidden @if (!count($users) == 0 ) border @endif border-gray-300 overflow-hidden w-full rounded-lg text-sm text-gray-700 absolute top-full z-10">
        @if (count($users) > 1)
        <li class="px-3 py-1 cursor-pointer hover:bg-blue-400 hover:text-white">
            <span data-id='0' class="block select-{{ $name }}-choose select-choose">--Chọn tất cả--</span>
        </li>
        @endif
        @foreach ($users as $user)
            @php
                $select_dependents[] = "#account-info-$user->id"
            @endphp
        <li class="flex items-center justify-between px-3 py-1 cursor-pointer hover:bg-blue-400 hover:text-white">
            <span data-id='{{ $user->id }}' class="flex-grow select-{{ $name }}-choose select-choose">{{ $user->name }}</span>
            <span data-id='{{ $user->id }}' class="text-sm font-medium open-user-detail-btn">chi tiết</span>
        </li>
        @endforeach
    </ul>
    <div id="select-{{ $name }}" class="relative text-gray-500 border border-gray-300 rounded-lg select-user">
        <span class="absolute p-1 text-sm duration-300 -translate-y-1/2 bg-white select-none select-user-label top-1/2 left-3">{{ $label }}</span>
        <div class="h-[46px] flex items-center justify-between p-3">
            <span id="select-{{ $name }}-value" class="text-[12px] text-black line-clamp-1"></span>
            <span class="w-5 duration-300 select-user-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Chevron Down</title><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M112 184l144 144 144-144"/></svg>
            </span>
        </div>
    </div>
</div>
@include('lib.auto_toggle', ['toggle_btn' => "#select-$name", 'main' => "#select-$name-list", "dependents_element" => $select_dependents])
<script>
    (() => {
        let values = []
        const select_input = document.querySelector('#select-{{ $name }}')
        const select_options = document.querySelectorAll('.select-{{ $name }}-choose')
        const select_value = document.querySelector('#select-{{ $name }}-value')
        const select_input_wrap = document.querySelector('#select-{{ $name }}-inputs')
        select_options.forEach(option => {
            option.onclick = function() {
                const id = this.getAttribute('data-id')
                const is_exist = values.find(value => value.id == id)
                if(!is_exist) {
                    values = id == 0? []: values.filter(value => value.id != 0)
                    values.push({id: id, text: this.innerText})
                }
                else {
                    values = values.filter(value => value.id != id);
                }
                
                select_options.forEach(_option => {
                    if(values.find(_value => _value.id == _option.getAttribute('data-id') ))
                        _option.classList.add('active')
                    else 
                        _option.classList.remove('active')
                })
                values.length? select_input.classList.add('valid'): select_input.classList.remove('valid')
                select_value.innerText = values.map(value => value.text).join(', ')
                select_input_wrap.innerHTML = values.map(value => `<input class='hidden' name='{{ $name }}[]' value='${value.id}' type="text">`).join('')
            }
        });
        @if(old($name))
            @foreach (old($name) as $old_id)
                [...select_options].find(option => option.getAttribute('data-id') == {{ $old_id }}).dispatchEvent(new Event('click'));
            @endforeach
        @endif
    })()
</script>
