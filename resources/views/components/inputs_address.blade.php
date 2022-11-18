{{-- related input_select logic --}}

<div class="input-address-province">
    @include('components.input_select', ['name' => 'province', 'label' => 'chọn tỉnh / thành phố', 'options' => []])
</div>
<div class="input-address-district">
    @include('components.input_select', ['name' => 'district', 'label' => 'chọn quận / huyện', 'options' => []])
</div>
<div class="input-address-subdistrict">
    @include('components.input_select', ['name' => 'sub_district', 'label' => 'chọn xã / phường', 'options' => []])
</div>
<script src={{ url('data_address.js') }}></script>
<script>
    const select_province = document.querySelector('.input-address-province select')
    const select_district = document.querySelector('.input-address-district select')
    const select_subdistrict = document.querySelector('.input-address-subdistrict select')
    render()
    function render() {
        select_province.innerHTML = render_options(Object.values(data_address))
        select_province.onchange = function(e) {
           handle_change_province(e.target.value)
        }
        select_district.onchange = function(e) {
            handle_change_district(e.target.value)
        }
    }
    function render_options(options) {
        return '<option value="" class="hidden"></option>' + options.map((option) => {
            return `<option value='${option.name}'>${option.name}</option>`
        }).join('')

    }
    
    function handle_change_province(value) {
            const options_district = Object.values(data_address).find(option => option.name == value)['quan-huyen']
            select_subdistrict.innerHTML = render_options([])
            select_district.innerHTML = render_options(Object.values(options_district))
    }

    function handle_change_district(value) {
        const options_subdistrict = Object.values(Object.values(data_address).find(option => option.name == select_province.value)['quan-huyen']).find(option => option.name == value)['xa-phuong']
        select_subdistrict.innerHTML = render_options(Object.values(options_subdistrict))
    }

</script>

@if (old('province'))
    <script>
        select_province.querySelectorAll('option').forEach(option => {
            if(option.value == "{{ old('province') }}") {
                option.setAttribute('selected', 'selected')
                select_province.setAttribute('value', "{{ old('province') }}")
                handle_change_province("{{ old('province') }}")
            }
        });
    </script>
@endif

@if(old('district'))
    <script>
        select_district.querySelectorAll('option').forEach(option => {
            if(option.value == "{{ old('district') }}") {
                option.setAttribute('selected', 'selected')
                select_district.setAttribute('value', "{{ old('district') }}")
                handle_change_district("{{ old('district') }}")
            }
        })
    </script>
@endif

@if (old('sub_district'))
    <script>
        select_subdistrict.querySelectorAll('option').forEach(option => {
            if(option.value == "{{ old('sub_district') }}") {
            option.setAttribute('selected', 'selected')
            select_subdistrict.setAttribute('value', "{{ old('sub_district') }}")
            }
        })
    </script>
@endif
