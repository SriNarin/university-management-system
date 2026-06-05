<x-filament-widgets::widget>
    <div class="space-y-6">
        
        @php
            $profile = $this->getStudentProfile();
            $gridData = $this->getTimetableGrid();
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        @endphp {{-- 🌟 FIXED: Changed from @php to @endphp to properly evaluate variables --}}

        {{-- ACADEMIC PROFILE BLOCK --}}
        @if($profile)
        <div class="fi-wi-card relative rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 pb-4 dark:border-gray-800">
                <div class="flex items-center gap-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-950 dark:text-primary-400">
                        <span class="text-base">🎓</span>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Active Student Academic Profile</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Your verified registration track matrix and core institutional profile tracking pathways.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center rounded-md bg-primary-50 px-2.5 py-1 text-xs font-bold text-primary-700 ring-1 ring-inset ring-primary-700/10 dark:bg-primary-500/10 dark:text-primary-400">
                        Class: {{ $profile['class_code'] }}
                    </span>
                    <span class="inline-flex items-center rounded-md bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-700/10 dark:bg-amber-500/10 dark:text-amber-400">
                        🚪 Room: {{ $profile['room_number'] }}
                    </span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4 text-xs sm:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-950/50">
                    <div class="text-gray-400 dark:text-gray-500">Faculty / Department</div>
                    <div class="mt-1 font-bold text-gray-900 dark:text-white">{{ $profile['academic_structure']['department']['faculty']['name_en'] ?? 'Engineering' }}</div>
                    <div class="text-[10px] text-gray-500 font-medium">{{ $profile['academic_structure']['department']['name_en'] ?? 'ITE' }}</div>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-950/50">
                    <div class="text-gray-400 dark:text-gray-500">Academic Level</div>
                    <div class="mt-1 font-bold text-gray-900 dark:text-white uppercase tracking-wide">{{ $profile['academic_structure']['academic_level'] ?? 'Bachelor' }}</div>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-950/50">
                    <div class="text-gray-400 dark:text-gray-500">Cohort Track Matrix</div>
                    <div class="mt-1 font-bold text-gray-900 dark:text-white">Gen {{ $profile['academic_structure']['generation'] ?? 'N/A' }}</div>
                    <div class="text-[10px] text-primary-600 font-bold uppercase tracking-tight">{{ str_replace('_', ' ', $profile['academic_structure']['year_progress'] ?? '') }}</div>
                </div>
                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-950/50">
                    <div class="text-gray-400 dark:text-gray-500">Operational Shift</div>
                    <div class="mt-1 font-bold text-gray-900 dark:text-white capitalize">☀️ {{ $profile['shift'] ?? 'Morning' }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- CLASS TIMETABLE SCHEDULE IN SPREADSHEET MATRIX DESIGN (IMAGE 2 UI) --}}
        <div class="fi-wi-card relative rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3 border-b border-gray-100 pb-4 dark:border-gray-800">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-950 dark:text-primary-400">
                    <span class="text-base">📅</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">CLASSROOM SCHEDULE TIMETABLE MATRIX</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Structured structural spreadsheet columns view showing daily curriculum track distribution.</p>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 shadow-xs">
                <table class="w-full table-fixed divide-y divide-gray-200 text-center text-xs dark:divide-gray-800 border-collapse">
                    <thead class="bg-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-4 w-44 bg-gray-200 dark:bg-gray-950 font-extrabold text-primary-700 dark:text-primary-400 border border-gray-200 dark:border-gray-800">
                                🕒 TIME BLOCK
                            </th>
                            @foreach($days as $day)
                                <th class="px-2 py-4 border border-gray-200 dark:border-gray-800">
                                    {{ $day }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-transparent">
                        @forelse($gridData as $timeBlock => $daysArray)
                            <tr class="hover:bg-gray-50/40 transition-colors">
                                <td class="px-3 py-4 font-mono font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-950/40 border border-gray-200 dark:border-gray-800 text-center">
                                    {{ $timeBlock }}
                                </td>

                                @foreach($days as $day)
                                    <td class="p-2 border border-gray-200 dark:border-gray-800 min-h-[90px] vertical-middle">
                                        @if(isset($daysArray[$day]) && $daysArray[$day] !== null)
                                            <div class="rounded-lg border border-primary-200 bg-primary-50/50 p-2.5 text-left transition-all hover:shadow-xs dark:border-primary-800 dark:bg-primary-950/30 border-l-4 border-l-primary-500">
                                                <div class="font-bold text-gray-900 dark:text-white text-xs leading-tight line-clamp-2 mb-1.5">
                                                    {{ $daysArray[$day]['subject'] }}
                                                </div>
                                                <div class="space-y-0.5 text-[10px] text-gray-500 dark:text-gray-400 font-medium">
                                                    <div class="truncate">👨‍🏫 {{ $daysArray[$day]['teacher'] }}</div>
                                                    <div class="inline-block mt-1 px-1.5 py-0.5 font-mono text-[9px] font-bold bg-white border border-gray-200 rounded dark:bg-gray-900 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                                        🚪 Rm {{ $daysArray[$day]['room'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="py-4 text-[10px] text-gray-300 dark:text-gray-700 italic font-normal select-none">
                                                —
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-xs italic text-gray-400 dark:text-gray-500">
                                    No scheduled training session tracking profiles discovered inside database logs.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>