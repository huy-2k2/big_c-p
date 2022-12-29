@extends('layouts.admin')
@section('content')
    @php
        $tbody = []
    @endphp
    @foreach ($product_lines as $product_line)
        @php
            $tbody[] = [
                'data-id' => $product_line->id,
                "<input data-id='$product_line->id' data-origin='$product_line->name' class='product_line-input product_line-name-$product_line->id px-2 py-1 border border-transparent focus:outline-none focus:border-blue-600' value='$product_line->name'/>",
                "<input data-id='$product_line->id' data-origin='$product_line->warranty_time' class='product_line-input product_line-period-$product_line->id px-2 py-1 border border-transparent focus:outline-none focus:border-blue-600' value='$product_line->warranty_time'/>",
                "<div data-id='$product_line->id' data-origin='$product_line->property' contenteditable='true' class='product_line-input product_line-property-$product_line->id px-2 py-1 border border-transparent focus:outline-none focus:border-blue-600'>$product_line->property</div>",
                ['title' => 'thay đổi', 'class' => 'font-medium hover:underline text-blue-600 cursor-pointer alter_product_line_btn'],
                ['title' => 'hoàn tác', 'class' => "refresh_product_line-$product_line->id font-medium hover:underline text-gray-600 cursor-pointer pointer-events-none refresh_btn"],
            ]
        @endphp
       
    @endforeach
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
        @include('components.table', ['title' => 'dòng sản phẩm', 'ths' => ['tên',  'số tháng bảo hành', 'thuộc tính', ['title' => 'thay đổi', 'sr_only' => true], ['title' => 'hoàn tác', 'sr_only' => true]], 'tbody' => $tbody])
    </div>
    <script>
        (() => {
            const alter_product_line_btns = document.querySelectorAll('.alter_product_line_btn')
            const refresh_product_line_btns = document.querySelectorAll('.refresh_product_line_btn')
            const product_line_inputs = document.querySelectorAll('.product_line-input');
            product_line_inputs.forEach(input => {
                input.onkeyup = function() {
                    const id = this.getAttribute('data-id')
                    const name = document.querySelector(`.product_line-name-${id}`)
                    const period = document.querySelector(`.product_line-period-${id}`)
                    const property = document.querySelector(`.product_line-property-${id}`)
                    const refresh_btn = document.querySelector(`.refresh_product_line-${id}`)
                    console.log(name.value, period.value, property.innerText);
                    if(name.value == name.getAttribute('data-origin') && period.value == period.getAttribute('data-origin') && property.innerText == property.getAttribute('data-origin')) {
                        console.log(123);
                        refresh_btn.classList.remove('active')
                    } else {
                        console.log(456);
                        refresh_btn.classList.add('active')
                    }
                }
            })
            refresh_product_line_btns.forEach(btn => {
                btn.onclick = function() {
                    const id = this.getAttribute('data-id')
                    const name = document.querySelector(`.product_line-name-${id}`)
                    const period = document.querySelector(`.product_line-period-${id}`)
                    const property = document.querySelector(`.product_line-property-${id}`)
                    name.value = name.getAttribute('data-origin')
                    period.value = period.getAttribute('data-origin')
                    property.innerText = property.getAttribute('data-origin')
                    this.classList.remove('active')
                }
            })
            alter_product_line_btns.forEach(btn => {
                btn.onclick = function() {
                    const id = this.getAttribute('data-id')
                    const name = document.querySelector(`.product_line-name-${id}`)
                    const period = document.querySelector(`.product_line-period-${id}`)
                    const property = document.querySelector(`.product_line-property-${id}`)
                    axios.post('{{ route('admin.product_line.update')}}', {
                        access_token: getCookie('access_token'),
                        user_id: '{{ Auth::user()->id }}',
                        'product_line_id': id,
                        'name': name.value,
                        'property': property.innerText,
                        'warranty_time': period.value
                    }).then(({data}) => {
                        name.setAttribute('data-origin', name.value)
                        period.setAttribute('data-origin', period.value)
                        property.setAttribute('data-origin', property.innerText)
                        document.querySelector(`.refresh_product_line-${id}`).classList.remove('active')
                        toastr.options = {
                                "closeButton" : true,
                                "progressBar" : true
                        }
                        toastr.success('thay đổi dòng sản phẩm thành công');
                    }).catch(({response: {data: {errors}}}) => {
                        for (const key in errors) {
                            toastr.options = {
                                "closeButton" : true,
                                "progressBar" : true
                            }
                            toastr.error(errors[key][0]);
                        }
                    })
                }
            });
        })()
    </script>
@endsection