<script>
    (() => {
        const toggle_btn = document.querySelector('{{ $toggle_btn }}')
        const main = document.querySelector('{{ $main }}')
        toggle_btn.onclick = () => main.classList.toggle('active')
        window.addEventListener('click', handle_click)
        function handle_click(e) {
            if(!main.contains(e.target) && !toggle_btn.contains(e.target)) {
                let is_accept = true
                @if(isset($dependents_element))
                    @foreach ($dependents_element as $dependent)
                        if(document.querySelector('{{ $dependent }}').contains(e.target))
                            is_accept = false
                    @endforeach
                @endif
                if(is_accept)
                    main.classList.remove('active')
            }
        }
    })()
</script>


