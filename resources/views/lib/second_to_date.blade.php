@if (floor($second / 31536000) >= 1)
    <span>{{ floor($second / 31536000) }} năm trước</span>
@elseif(floor($second / 2592000) >= 1)
    <span>{{ floor($second / 2592000)}} tháng trước</span>
@elseif(floor($second / 604800) >= 1)
    <span>{{ floor($second / 604800) }} tuần trước</span>
@elseif(floor($second / 86400) >= 1)
    <span>{{ floor($second / 86400) }} ngày trước</span>
@elseif(floor($second / 3600) >= 1)
    <span>{{ floor($second / 3600) }} giờ trước</span>
@elseif(floor($second / 60) >= 1)
    <span>{{ floor($second / 60)}} phút trước</span>
@else
    <span>{{ $second }} giây trước</span>
@endif
