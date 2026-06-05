<x-filament-widgets::widget>
    @php
        $gridData = $this->getTimetableGrid();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    @endphp

    <div class="fi-wi-card relative rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        {{-- HEADER COMPONENT --}}
        <div class="flex items-center gap-x-3 border-b border-gray-100 pb-4 dark:border-gray-800">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-950 dark:text-primary-400">
                <span class="text-base">📅</span>
            </div>
            <div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">My Daily Teaching Schedule Matrix</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Your weekly structural curriculum tracking matrix mapped from Monday through Sunday logs.</p>
            </div>
        </div>

        {{-- SPREADSHEET MATRIX DESIGN --}}
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
                            {{-- Time block column --}}
                            <td class="px-3 py-4 font-mono font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-950/40 border border-gray-200 dark:border-gray-800 text-center">
                                {{ $timeBlock }}
                            </td>

                            {{-- Days row mappings --}}
                            @foreach($days as $day)
                                <td class="p-2 border border-gray-200 dark:border-gray-800 min-h-[90px] vertical-middle">
                                    @if(isset($daysArray[$day]))
                                        <div class="rounded-lg border border-amber-200 bg-amber-50/40 p-2.5 text-left transition-all hover:shadow-xs dark:border-amber-900/50 dark:bg-amber-950/20 border-l-4 border-l-amber-500">
                                            <div class="font-bold text-gray-900 dark:text-white text-xs leading-tight line-clamp-2 mb-1">
                                                {{ $daysArray[$day]['subject'] }}
                                            </div>
                                            <div class="space-y-1 text-[10px] font-medium text-gray-500 dark:text-gray-400">
                                                <div class="inline-flex items-center rounded bg-gray-100 px-1.5 py-0.5 text-[9px] font-bold text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                                    🏫 Class: {{ $daysArray[$day]['class_code'] }}
                                                </div>
                                                <div class="block text-[9px] text-gray-400 font-mono">
                                                    🚪 Room {{ $daysArray[$day]['room'] }}
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
                                No assigned instructional teaching allocations found for your profile.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-widgets::widget>