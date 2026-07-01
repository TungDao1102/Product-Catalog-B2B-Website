@php
    $currentLocale = app()->getLocale();
    $flags = [
        'vi' => 'vn',
        'en' => 'us',
    ];
    $locales = [
        'vi' => 'Tiếng Việt',
        'en' => 'English',
    ];
    $currentPath = request()->path();
    // Remove current locale prefix from path to get base path
    $basePath = preg_replace('#^[a-z]{2}/?#', '', $currentPath);
    $basePath = ltrim($basePath, '/');
@endphp

<div class="language-switcher dropdown d-inline-block">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://flagcdn.com/16x12/{{ $flags[$currentLocale] }}.png" srcset="https://flagcdn.com/24x18/{{ $flags[$currentLocale] }}.png 2x" alt="" class="border" width="16" height="12" style="margin-top:-1px">
        {{ $locales[$currentLocale] }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach($locales as $localeCode => $localeLabel)
            @if($localeCode !== $currentLocale)
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ url($localeCode . ($basePath ? '/' . $basePath : '')) }}">
                        <img src="https://flagcdn.com/16x12/{{ $flags[$localeCode] }}.png" srcset="https://flagcdn.com/24x18/{{ $flags[$localeCode] }}.png 2x" alt="" class="border" width="16" height="12" style="margin-top:-1px">
                        {{ $localeLabel }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
