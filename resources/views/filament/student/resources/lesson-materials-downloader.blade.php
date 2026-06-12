@php
    $materials = \App\Models\LessonMaterial::where('class_schedule_id', $record->class_schedule_id)
        ->where('is_visible_to_students', true)
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

<div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <span class="p-1 rounded bg-info-500/10 text-info-600">📚</span> Shared Course Handouts & Lecture Materials
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($materials as $material)
            <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-col justify-between shadow-sm">
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $material->lecture_title_topic }}</h4>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Published: {{ $material->created_at->timezone('Asia/Phnom_Penh')->format('M d, Y h:i A') }}</p>
                </div>
                <a href="{{ route('materials.stream-download', ['id' => $material->id]) }}" 
                    class="inline-flex items-center justify-center gap-2 w-full px-3 py-2 text-xs font-semibold text-white bg-info-600 hover:bg-info-500 rounded-lg shadow transition-colors">
                        📥 Download Document Asset
                </a>
            </div>
        @empty
            <div class="col-span-full p-4 text-center text-xs text-gray-500 dark:text-gray-400 border border-dashed border-gray-200 dark:border-gray-700 rounded-lg">
                No lesson notes or presentations have been posted by the instructor for this class schedule yet.
            </div>
        @endforelse
    </div>
</div>