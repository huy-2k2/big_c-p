<ul>
    @foreach ($items as $item)
    <li>
        <a  @if(!isset($item['children'])) href="{{ $item['link'] }}" @endif  class="relative flex items-center w-full px-1 py-3 cursor-pointer sidebar-item gap-x-2 group">
            <span class="w-10 text-blue-500">
                {!! $item['icon'] !!}
            </span>
            <span class="font-semibold text-gray-600 capitalize sidebar-hidden text-md group-hover:text-gray-900">{{ $item['text'] }}</span>
            @isset($item['children'])
                <span class="sidebar-toggle-child duration-300 sidebar-hidden absolute right-0 top-1/2 -translate-y-1/2 cursor-pointer flex items-center justify-center w-6 p-[3px] text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endisset
        </a>
        @isset($item['children'])
            <ul class="hidden overflow-hidden duration-300 sidebar-children sidebar-hidden">
               @foreach ($item['children'] as $children)
               <li>
                    <a class="block pl-[50px] py-2 capitalize text-gray-500 text-[14px] hover:text-gray-800"  href={{ $children['link'] }}>{{ $children['text'] }}</a>
                </li>
               @endforeach
            </ul>
        @endisset
      
    </li>
    @endforeach
</ul>

<script>
    const sidebar_items = document.querySelectorAll('.sidebar-item')
    sidebar_items.forEach(sidebar_items => {
        sidebar_items.onclick = function() {
            this.classList.toggle('active')
        }
    });
</script>