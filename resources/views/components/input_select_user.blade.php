@php
    $dependents_element = []    
@endphp
@foreach ($users as $user)
    @php
        $dependents_element[] = "#account-info-$user->id"
    @endphp
@endforeach
@include('components.input_select_advance', ['options' => $users, 'name' => $name, 'label' => $label, 'dependents_element' => $dependents_element, 'detail_option' => ['class' => 'open-user-detail-btn', 'title' => 'chi tiết']])