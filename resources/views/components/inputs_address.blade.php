{{-- related input_select logic --}}

    @include('components.input_select', ['name' => 'province', 'label' => 'chọn tỉnh / thành phố', 'options' => []])
    @include('components.input_select', ['name' => 'district', 'label' => 'chọn quận / huyện', 'options' => []])
    @include('components.input_select', ['name' => 'sub_district', 'label' => 'chọn xã / phường', 'options' => []])
<script src={{ url('data_address.js') }}></script>
<script>
    const select_province = document.querySelector('#input-select-province')
    const select_district = document.querySelector('#input-select-district')
    const select_subdistrict = document.querySelector('#input-select-sub_district')
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
    @include('lib.input_select', ['name' => 'province'])
   <script>
        handle_change_province("{{ old('province') }}")
   </script>
@endif

@if(old('district'))
    @include('lib.input_select', ['name' => 'district'])
   <script>
        handle_change_district("{{ old('district') }}")
   </script>
@endif

@if (old('sub_district'))
    @include('lib.input_select', ['name' => 'sub_district'])
@endif
