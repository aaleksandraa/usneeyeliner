@props(['name', 'class' => 'h-5 w-5'])

<svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('home')
            <path stroke-linecap="round" stroke-linejoin="round" d="m3 10.5 9-7.5 9 7.5v9a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 19.5v-9Z"/><path stroke-linecap="round" d="M9 21v-7.5h6V21"/>
            @break
        @case('users')
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-1.5A4.5 4.5 0 0 0 11.5 15h-5A4.5 4.5 0 0 0 2 19.5V21M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM17 11a4 4 0 0 0 0-8M22 21v-1.5a4.5 4.5 0 0 0-3-4.24"/>
            @break
        @case('courses')
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4.5A2.5 2.5 0 0 1 6.5 2H21v17H6.5A2.5 2.5 0 0 0 4 21.5v-17Z"/><path stroke-linecap="round" d="M4 19h17M8 6h8"/>
            @break
        @case('profile')
            <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4.5 21a7.5 7.5 0 0 1 15 0"/>
            @break
        @case('logout')
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5M14 8l4 4-4 4M8 12h10"/>
            @break
        @case('plus')
            <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
            @break
        @case('search')
            <circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-4-4"/>
            @break
        @case('arrow-right')
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-5-5 5 5-5 5"/>
            @break
        @case('play')
            <circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m10 8 6 4-6 4V8Z"/>
            @break
        @case('shield')
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
            @break
        @case('menu')
            <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
            @break
        @case('x')
            <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/>
            @break
        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/>
            @break
        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6"/>
            @break
        @case('lock')
            <rect x="4" y="10" width="16" height="11" rx="2"/><path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/>
            @break
        @case('eye')
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/>
            @break
        @case('sparkles')
            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.2 3.8L17 8l-3.8 1.2L12 13l-1.2-3.8L7 8l3.8-1.2L12 3ZM19 14l.7 2.3L22 17l-2.3.7L19 20l-.7-2.3L16 17l2.3-.7L19 14ZM5 13l.8 2.2L8 16l-2.2.8L5 19l-.8-2.2L2 16l2.2-.8L5 13Z"/>
            @break
        @default
            <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
