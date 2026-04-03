@props(['disabled' => false, 'numeric' => false])

@php
    $extraAttributes = [];
    if ($numeric) {
        $extraAttributes['oninput'] = "this.value = this.value.replace(/[^0-9]/g, '')";
    }
@endphp

<input {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge($extraAttributes)->merge(['class' => 'border-1 border-slate-400/30 bg-white focus:border-[#003366] focus:ring-0 rounded-lg h-12 px-4 w-full transition-all duration-200 text-sm placeholder:text-slate-400 placeholder:font-normal disabled:opacity-50 disabled:cursor-not-allowed font-medium']) }}>
