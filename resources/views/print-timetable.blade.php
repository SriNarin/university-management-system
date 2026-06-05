<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official_Timetable_{{ $class->class_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { padding: 0; margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white p-8 text-xs font-sans text-gray-900" onload="window.print()">

    {{-- PRINT TOOLBAR PANEL --}}
    <div class="no-print mb-6 p-4 bg-gray-50 border border-gray-200 rounded-xl flex justify-between items-center">
        <span class="text-gray-600 font-medium">📄 Ready to print or export as an official PDF file.</span>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold transition-all shadow-xs text-xs">
            🖨️ Save / Print Document
        </button>
    </div>

    <div class="border-b-2 border-gray-900 pb-4 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-lg font-bold uppercase tracking-tight text-gray-900">Royal University Management Portal</h1>
            <p class="text-gray-500 font-medium">Official Timetable Assignment File Matrix</p>
        </div>
        <div class="text-right">
            <div class="font-bold bg-gray-100 px-3 py-1.5 rounded-lg text-sm text-gray-800 border border-gray-200">CLASS: {{ $class->class_code }}</div>
            <p class="text-[10px] text-gray-400 mt-1">Released: {{ now()->format('d-M-Y h:i A') }}</p>
        </div>
    </div>

    <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200/60 grid grid-cols-2 gap-4 text-gray-700">
        <div><strong>🚪 Designated Room Location:</strong> Room {{ $class->room_number }}</div>
        <div><strong>☀️ Core Shift Framework:</strong> {{ ucfirst($class->shift) }}</div>
    </div>

    <table class="w-full text-center border-collapse border border-gray-300 rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-100 text-gray-700 font-bold">
                <th class="border border-gray-300 p-3 w-40 bg-gray-100">🕒 TIME WINDOW</th>
                @foreach($days as $day)
                    <th class="border border-gray-300 p-2 font-semibold">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($gridData as $timeBlock => $daysArray)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="border border-gray-300 p-3 font-mono font-bold bg-gray-50 text-gray-800">{{ $timeBlock }}</td>
                    @foreach($days as $day)
                        <td class="border border-gray-300 p-2 min-h-[60px]">
                            @if(isset($daysArray[$day]))
                                <div class="font-bold text-gray-900 text-xs">{{ $daysArray[$day]['subject'] }}</div>
                                <div class="text-[10px] text-gray-500 mt-1 font-medium">👨‍🏫 {{ $daysArray[$day]['teacher'] }}</div>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-12 flex justify-between text-center text-gray-500 text-[10px]">
        <div>
            <div class="w-36 border-b border-gray-400 mb-1 mx-auto"></div>
            <p>Prepared by Faculty Registrar</p>
        </div>
        <div>
            <div class="w-36 border-b border-gray-400 mb-1 mx-auto"></div>
            <p>Authorized Academic Board Seal</p>
        </div>
    </div>

</body>
</html>