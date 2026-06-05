<x-filament-panels::page>
    <form wire:submit.prevent="saveAttendanceSheet" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-3 justify-end">
            <x-filament::button type="submit" color="success" size="lg">
                Save Complete Class Attendance Sheet
            </x-filament::button>
            
            <a href="{{ \App\Filament\Teacher\Resources\Attendances\AttendanceResource::getUrl('index') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-400 hover:underline">
                Cancel
            </a>
        </div>
    </form>
</x-filament-panels::page>