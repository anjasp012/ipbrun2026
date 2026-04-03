@props(['type' => 'submit', 'variant' => 'primary', 'href' => null])

@php
    $baseClasses = 'inline-flex items-center px-4 py-2 border border-transparent font-bold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm';
    
    $variants = [
        'primary' => 'bg-blue-600 dark:bg-blue-500 text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:ring-blue-500',
        'secondary' => 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 border-slate-300 dark:border-slate-700 focus:ring-slate-500',
        'danger' => 'bg-red-600 dark:bg-red-500 text-white hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-500',
        'success' => 'bg-emerald-600 dark:bg-emerald-500 text-white hover:bg-emerald-700 dark:hover:bg-emerald-600 focus:ring-emerald-500',
        'navy' => 'bg-[#003366] text-white hover:bg-slate-800 focus:ring-blue-500',
        'orange' => 'bg-[#E8630A] text-white hover:bg-orange-700 focus:ring-orange-500',
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
