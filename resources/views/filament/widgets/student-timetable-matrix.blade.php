<x-filament-widgets::widget>
    @php
        $classes = $this->getEnrolledClasses();
        $profile = $this->getStudentProfile();
        $gridData = $this->getTimetableGrid();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        $isFacultyManager = auth()->user()->role === 'faculty_manager';
        $isStudent = auth()->user()->role === 'student';
    @endphp

    <div class="space-y-6">
        
        {{-- SELECTION CONTROLLER --}}
        <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-900 dark:border-gray-800 no-print">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">📅 Academic Class Matrix Stream</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Select an active class stream option below.</p>
                </div>
                <div class="w-full sm:w-64">
                    <select wire:model.live="activeClassId" class="block w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        @foreach($classes as $id => $code)
                            <option value="{{ $id }}">Class: {{ $code }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- STATUS CONTROL BAR --}}
        @if($profile)
            <div class="p-4 rounded-xl border no-print {{ $profile['is_timetable_published'] ? 'bg-emerald-50 border-emerald-200 dark:bg-emerald-950/20 dark:border-emerald-900/50' : 'bg-amber-50 border-amber-200 dark:bg-amber-950/20 dark:border-amber-900/50' }} flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-x-3">
                    <span class="text-2xl">{{ $profile['is_timetable_published'] ? '📜' : '⏳' }}</span>
                    <div>
                        <h4 class="text-xs font-bold {{ $profile['is_timetable_published'] ? 'text-emerald-900 dark:text-emerald-400' : 'text-amber-900 dark:text-amber-400' }}">
                            {{ $profile['is_timetable_published'] ? 'Official Timetable Released' : 'Draft Schedule Phase' }}
                        </h4>
                        <p class="text-[11px] {{ $profile['is_timetable_published'] ? 'text-emerald-600 dark:text-emerald-500' : 'text-amber-600 dark:text-amber-500' }}">
                            {{ $profile['is_timetable_published'] ? 'This file is published and fully visible on student dashboards.' : 'Schedules are currently hidden from students.' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($isFacultyManager)
                        <button type="button" wire:click="togglePublishStatus" class="rounded-lg px-3 py-1.5 text-xs font-bold text-white transition-all shadow-xs {{ $profile['is_timetable_published'] ? 'bg-red-600 hover:bg-red-500' : 'bg-blue-600 hover:bg-blue-500' }}">
                            {{ $profile['is_timetable_published'] ? '🛑 Retract & Unpublish' : '🚀 Generate & Post to Students' }}
                        </button>
                    @endif

                    @if(($isStudent && $profile['is_timetable_published']) || $isFacultyManager)
                        <button type="button" wire:click="triggerPrintLayout" class="rounded-lg bg-gray-900 hover:bg-gray-800 dark:bg-gray-800 dark:hover:bg-gray-700 px-3 py-1.5 text-xs font-bold text-white shadow-xs transition-all">
                            🖨️ Print / Download PDF
                        </button>
                    @endif
                </div>
            </div>
        @endif

        {{-- MAIN TABLE TIMETABLE MATRIX GRID --}}
        @if(!$isStudent || ($isStudent && ($profile['is_timetable_published'] ?? false)))
            <div id="timetable-printable-area" class="p-5 bg-white rounded-xl border border-gray-200 shadow-xs dark:bg-gray-900 dark:border-gray-800 print:border-none print:shadow-none">
                
                {{-- Only visible during paper print output --}}
                <div class="hidden print:block mb-6 border-b pb-4">
                    <h1 class="text-xl font-bold uppercase">Royal University Management Portal</h1>
                    <p class="text-xs text-gray-500">Official Class Timetable Document</p>
                    <p class="text-xs font-bold mt-2">CLASS CODE: {{ $profile['class_code'] ?? '' }} | ROOM: {{ $profile['room_number'] ?? '' }}</p>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 print:border-collapse">
                    <table class="w-full table-fixed divide-y divide-gray-200 text-center text-xs dark:divide-gray-800 border-collapse">
                        <thead class="bg-gray-50 text-[11px] font-bold uppercase text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                            <tr>
                                <th class="px-3 py-4 w-44 bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 print:bg-gray-100">🕒 TIME WINDOW</th>
                                @foreach($days as $day)
                                    <th class="px-2 py-4 border border-gray-200 dark:border-gray-800 font-semibold">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800">
                            @forelse($gridData as $timeBlock => $daysArray)
                                <tr>
                                    <td class="px-3 py-4 font-mono font-bold text-gray-900 dark:text-white bg-gray-50/60 border border-gray-200 dark:border-gray-800 print:bg-gray-50">
                                        {{ $timeBlock }}
                                    </td>
                                    @foreach($days as $day)
                                        <td class="p-2 border border-gray-200 dark:border-gray-800">
                                            @if(isset($daysArray[$day]))
                                                <div class="rounded-lg border border-blue-200 bg-blue-50/40 p-2 text-left dark:border-blue-900/50 dark:bg-blue-950/20 border-l-4 border-l-blue-500 print:border-gray-300">
                                                    <div class="font-bold text-gray-900 dark:text-white text-xs leading-tight mb-1">
                                                        {{ $daysArray[$day]['subject'] }}
                                                    </div>
                                                    <div class="text-[10px] font-medium text-gray-500 dark:text-gray-400">
                                                        👨‍🏫 {{ $daysArray[$day]['teacher'] }}
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-300 dark:text-gray-700">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-xs italic text-gray-400 dark:text-gray-500">
                                        No active schedule parameters mapped out for this timeline grid.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- LOCKED BLANKSLATE --}}
            <div class="p-12 text-center bg-gray-50 border border-dashed border-gray-300 rounded-2xl dark:bg-gray-900/40 dark:border-gray-800">
                <div class="text-4xl mb-3">🔒</div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Timetable Under Faculty Review</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto mt-1">The operations matrix is currently being assigned by management. It will become viewable and downloadable here as soon as it is published.</p>
            </div>
        @endif

    </div>

    {{-- CLEAN BROWSER PRINT LOGIC RUNNER --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('open-timetable-print-window', event => {
                // Injects temporary print styles to clean up headers/sidebars cleanly
                const style = document.createElement('style');
                style.id = 'timetable-print-styles';
                style.innerHTML = `
                    @media print {
                        body * { display: none !important; }
                        #timetable-printable-area, #timetable-printable-area * { display: block !important; }
                        .no-print { display: none !important; }
                    }
                `;
                document.head.appendChild(style);
                
                // Triggers native system save-dialog/print instantly
                window.print();
                
                // Cleanup styles after print execution finishes
                setTimeout(() => {
                    const el = document.getElementById('timetable-print-styles');
                    if(el) el.remove();
                }, 1000);
            });
        });
    </script>
</x-filament-widgets::widget>