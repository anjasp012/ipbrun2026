<x-layouts.app :withoutNavbar="true">
    <div class="fixed inset-0 w-full h-full bg-[#003366] overflow-hidden flex flex-col items-center justify-end pb-20">
        {{-- Desktop Background --}}
        <div class="absolute inset-0 hidden md:block w-full h-full bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('assets/images/cooming_soon_desktop.png') }}')">
        </div>
        {{-- Mobile Background --}}
        <div class="absolute inset-0 block md:hidden w-full h-full bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('assets/images/cooming_soon_mobile.png') }}')">
        </div>
    </div>
</x-layouts.app>
