@extends('layouts.app')

@section('title', 'Tailwind 4 Test')

@section('content')
<div class="flex flex-col items-center justify-center space-y-10 min-h-[70vh]">
    <div class="p-1 w-fit rounded-2xl bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 shadow-2xl hover:scale-105 transition-transform duration-300">
        <div class="bg-white dark:bg-slate-900 rounded-xl p-8 max-w-md text-center">
            <h1 class="text-4xl font-bold font-outfit mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                Tailwind v4 is Ready! 🚀
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-lg mb-6 leading-relaxed">
                Your Laravel project is already configured with the latest version of Tailwind CSS.
            </p>
            <div class="flex gap-4 justify-center">
                <span class="px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-sm font-semibold">
                    Vite Integrated
                </span>
                <span class="px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full text-sm font-semibold">
                    JIT Powered
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full mt-12 bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="space-y-6">
            <h2 class="text-2xl font-bold font-outfit">Component Demo</h2>
            <p class="text-slate-500 dark:text-slate-400">Testing our new reusable Blade components with consistent theme.</p>
            
            <form action="#" class="space-y-4">
                <div>
                    <x-label for="name" value="Ticket Name" />
                    <x-input id="name" type="text" placeholder="e.g. Early Bird" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="price" value="Price" />
                        <x-input id="price" type="number" placeholder="50000" />
                    </div>
                    <div>
                        <x-label for="category" value="Category" />
                        <x-select id="category" placeholder="Select category">
                            <option value="1">Regular</option>
                            <option value="2">VIP</option>
                        </x-select>
                    </div>
                </div>

                <div>
                    <x-label for="description" value="Description" />
                    <x-textarea id="description" placeholder="Short ticket description..."></x-textarea>
                </div>

                <div class="flex gap-2">
                    <x-button variant="primary">Save Ticket</x-button>
                    <x-button variant="secondary">Cancel</x-button>
                    <x-button variant="danger">Delete</x-button>
                </div>
            </form>
        </div>

        <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 flex flex-col justify-center">
            <h4 class="text-center font-semibold mb-4">How to Use:</h4>
            <pre class="text-xs bg-slate-900 text-slate-300 p-4 rounded-lg overflow-x-auto">
&lt;x-label for="name" value="Name" /&gt;
&lt;x-input id="name" /&gt;

&lt;x-select id="cat" placeholder="Pick one"&gt;
  &lt;option&gt;Option 1&lt;/option&gt;
&lt;/x-select&gt;

&lt;x-button variant="primary"&gt;
  Submit
&lt;/x-button&gt;</pre>
        </div>
    </div>
</div>
@endsection
