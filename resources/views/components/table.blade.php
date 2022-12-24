
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-left text-gray-900 capitalize bg-white dark:text-white dark:bg-gray-800">
            {{ $title }}
        </caption>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                  STT
                </th>
                @foreach ($ths as $th)
                <th scope="col" class="px-6 py-3 whitespace-nowrap">
                    @if(isset($th['sr_only']))
                        <span class="sr-only">{{ $th['title'] }}</span>
                    @else 
                        {{ $th }}
                    @endif
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white">
            @php
                $stt = 0    
            @endphp
            @foreach ($tbody as $tr)
                @php
                    $stt++    
                @endphp
                <tr>
                    <th @isset($tr['data-id']) data-id="{{ $tr['data-id'] }}" @endisset scope="row" class="px-6 py-4 font-medium text-gray-900 stt whitespace-nowrap">
                        {{ $stt }}
                    </th>
                    @foreach ($tr as $key => $td)
                        @if(!is_string($key))
                            <td @isset($tr['data-id']) data-id="{{ $tr['data-id'] }}" @endisset class="px-6 py-4 whitespace-nowrap @isset($td['class']) {{ $td['class'] }} @endisset">
                                {!! $td['title'] ?? $td !!}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
