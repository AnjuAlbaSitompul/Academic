<div class="page-header">
    <h1 class="page-title">
        {{ ucwords(str_replace('-', ' ', request()->segment(1) ?? 'Home')) }}
    </h1>

    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}">Home</a>
            </li>

            @php $url = ''; @endphp

            @foreach (request()->segments() as $index => $segment)
                @php $url .= '/'.$segment; @endphp

                @if ($loop->last)
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ ucwords(str_replace('-', ' ', $segment)) }}
                    </li>
                @else
                    <li class="breadcrumb-item">
                        <a href="{{ url($url) }}">
                            {{ ucwords(str_replace('-', ' ', $segment)) }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ol>
    </div>
</div>
