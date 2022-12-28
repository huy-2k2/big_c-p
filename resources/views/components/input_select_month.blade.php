@php
    $dependents_element = []    
@endphp
@foreach ($months as $month)
    @php
        $dependents_element[] = "#account-info-$month"
    @endphp
@endforeach
@include('components.input_select_advance', ['options' => $months, 'name' => $name, 'label' => $label, 'detail_option' => ['class' => 'open-user-detail-btn', 'title' => 'chi tiết']])