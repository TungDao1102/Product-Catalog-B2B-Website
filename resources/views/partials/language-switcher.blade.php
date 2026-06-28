@php
    $currentLocale = app()->getLocale();
    $locales = [
        'vi' => 'VI',
        'en' => 'EN',
    ];
    $currentPath = request()->path();
    // Remove current locale prefix from path to get base path
    $basePath = preg_replace('#^[a-z]{2}/?#', '', $currentPath);
    $basePath = ltrim($basePath, '/');
@endphp

<div class="language-switcher dropdown d-inline-block">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ strtoupper($currentLocale) }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach($locales as $localeCode => $localeLabel)
            @if($localeCode !== $currentLocale)
                <li>
                    <a class="dropdown-item" href="{{ url($localeCode . ($basePath ? '/' . $basePath : '')) }}">
                        {{ $localeLabel }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
