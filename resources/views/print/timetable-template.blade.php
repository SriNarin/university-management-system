<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Academic Timetable - {{ $schoolClass->class_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none; }
            .print-container { border: none; shadow: none; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 p-6 antialiased">

    <div class="max-w-4xl mx-auto mb-4 flex justify-between items-center no-print bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <span class="text-sm text-gray-600 font-medium">📋 Document generate Ready. Ready for download or direct files printing.</span>
        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm px-4 py-2 rounded-lg transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Schedule Timetable File
        </button>
    </div>

    <div class="print-container max-w-4xl mx-auto bg-white border border-gray-200 p-8 rounded-xl shadow-sm">
        
        <div class="border-b-2 border-gray-800 pb-4 mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-xl font-black tracking-wide text-gray-900 uppercase">Royal University of Phnom Penh</h1>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">University Management System Engine</p>
                <p class="text-xs text-gray-500 mt-1">Generated: {{ now()->format('d-M-Y h:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-4 py-1.5 bg-emerald-50 border border-emerald-300 text-emerald-800 font-mono font-bold rounded-lg text-sm shadow-sm">
                    Class: {{ $schoolClass->class_code }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6 text-xs bg-gray-50 border border-gray-200/60 p-4 rounded-xl">
            <div><span class="text-gray-400 block font-medium uppercase tracking-wider">Room Allocation</span><strong class="text-gray-800 text-sm">Room {{ $schoolClass->room_number }}</strong></div>
            <div><span class="text-gray-400 block font-medium uppercase tracking-wider">Academic Shift</span><strong class="text-gray-800 text-sm">{{ ucfirst($schoolClass->shift) }}</strong></div>
            <div><span class="text-gray-400 block font-medium uppercase tracking-wider">Semester Term</span><strong class="text-gray-800 text-sm">{{ strtoupper(str_replace('_', ' ', $schoolClass->semester)) }}</strong></div>
        </div>

        <div class="overflow-hidden border border-gray-200 rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900 text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="p-3.5">Day of Week</th>
                        <th class="p-3.5">Subject </th>
                        <th class="p-3.5 text-center">Subject Code</th>
                        <th class="p-3.5 text-right">lecturer Name</th>
                        <th class="p-3.5 text-right">Study Time </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="p-3.5 font-bold text-gray-900 border-r border-gray-100 bg-gray-50/40 w-32">{{ $schedule->day_of_week }}</td>
                            <td class="p-3.5 font-medium text-emerald-700">{{ $schedule->subject_name_en }}</td>
                            <td class="p-3.5 text-center font-mono text-xs text-gray-500"><span class="bg-gray-100 px-2 py-0.5 rounded border">{{ $schedule->subject_code }}</span></td>
                            <td class="p-3.5 font-medium text-emerald-700 text-right">{{ $schedule->teacher->name ?? 'No Teacher' }}</td>
                            <td class="p-3.5 text-right font-mono text-xs font-semibold text-gray-600">
                                {{ date('h:i A', strtotime($schedule->start_time)) }} - {{ date('h:i A', strtotime($schedule->end_time)) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400 italic bg-gray-50/30">
                                ⚠️ No schedule items have been compiled into this layout vector file yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-12 pt-8 border-t border-dashed border-gray-200 flex justify-between text-xs text-gray-400 font-medium">
            <p>Verification Code: {{ md5($schoolClass->id . $schoolClass->class_code) }}</p>
            <p class="text-right border-t border-gray-300 pt-4 w-48 text-center text-gray-600 font-semibold">Faculty Manager Head Department Signature</p>
        </div>
    </div>

</body>
</html>