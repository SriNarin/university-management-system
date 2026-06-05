<x-filament-panels::page>
    <div class="space-y-6">
        <form wire:submit="saveProfile" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end gap-3">
                <x-filament::button type="submit" size="md" color="primary" icon="heroicon-m-check-circle">
                    Save Structural Updates
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>