<script>
    (() => {
        const input_select = document.querySelector('#input-select-{{ $name }}')
        input_select.querySelectorAll('option').forEach(option => {
        if(option.value == "{{ old($name) }}") {
            option.setAttribute('selected', 'selected')
            input_select.setAttribute('value', '{{ old($name) }}')            
        }
      });
    })()
</script>