<x-filament-widgets::widget>
    <x-filament::card class="p-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 dark:border-gray-700 pb-4 mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">
                    Welcome Back, {{ Auth::user()?->name }}!
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    University System Tracking Portal Engine
                </p>
            </div>
            
            <div class="flex gap-2 items-center">
                <span class="px-3 py-1 bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400 rounded-full font-semibold text-xs border border-primary-200 dark:border-primary-800 tracking-wider">
                    🏛️ {{ $this->getUserData()['role'] }}
                </span>
                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 rounded-full font-medium text-xs border border-emerald-200 dark:border-emerald-800">
                    🟢 {{ $this->getUserData()['status'] }}
                </span>
            </div>
        </div>

        {{-- Live Filament Profile Configuration Form --}}
        <form wire:submit="saveProfile" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end pt-2">
                <x-filament::button type="submit" color="primary" icon="heroicon-m-check-badge">
                    Save Account Changes
                </x-filament::button>
            </div>
        </form>
    </x-filament::card>
</x-filament-widgets::widget>