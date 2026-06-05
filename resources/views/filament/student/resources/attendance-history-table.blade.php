<!-- @php
    $logs = \App\Models\Attendance::where('class_schedule_id', $record->class_schedule_id)
        ->where('student_id', auth()->id())
        ->orderBy('teaching_date', 'desc')
        ->get();
@endphp

<div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <span class="p-1 rounded bg-warning-500/10 text-warning-600">🗓️</span> Your Historical Session Attendance Tracker
    </h3>
    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-2.5 text-left font-semibold text-gray-600 dark:text-gray-400">Session Date</th>
                    <th class="px-4 py-2.5 text-left font-semibold text-gray-600 dark:text-gray-400">Tracked Metrics Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-2.5 font-medium text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($log->teaching_date)->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 font-medium
                                {{ $log->status === 'present' ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400' : '' }}
                                {{ $log->status === 'absent' ? 'bg-danger-50 text-danger-700 dark:bg-danger-500/10 dark:text-danger-400' : '' }}
                                {{ $log->status === 'late' ? 'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400' : '' }}
                                {{ $log->status === 'permission' ? 'bg-info-50 text-info-700 dark:bg-info-500/10 dark:text-info-400' : '' }}">
                                {{ strtoupper($log->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-4 text-center text-gray-400 dark:text-gray-500">No session tracking entries recorded for you in this course schedule yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> -->