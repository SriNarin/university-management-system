<div class="flex items-center justify-center gap-3 px-2 py-1">
    
    <form action="{{ route('timetable.toggle-status', $getRecord()->id) }}" 
          method="POST" 
          class="inline-flex items-center"
          onclick="event.stopPropagation();">
        @csrf
        
        @if($getRecord()->is_timetable_published)
            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition shadow-sm">
                <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                🖨️Tap to Print Timetables
            </button>
        @else
            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-700 border border-gray-300 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition shadow-sm">
                ⚙️ Generate Timetable
            </button>
        @endif
    </form>

    @if($getRecord()->is_timetable_published)
        <a href="{{ route('timetable.print', $getRecord()->id) }}" 
           target="_blank" 
           onclick="event.stopPropagation();"
           class="inline-flex items-center justify-center p-1 text-emerald-600 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg transition border border-emerald-200 bg-white"
           title="Open Printable Schedule Document">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l2.9-2.9m0 0l2.9 2.9m-2.9-2.9v6c0 1.1.9 2 2 2h6.28a2.25 2.25 0 002.25-2.25V5.25a2.25 2.25 0 00-2.25-2.25H4.5A2.25 2.25 0 002.25 5.25v13.5A2.25 2.25 0 004.5 21h2.25" />
            </svg>
        </a>
    @else
        <span class="text-[11px] text-gray-400 font-mono tracking-wider select-none bg-gray-50 px-1.5 py-0.5 rounded border border-gray-200/50">Unpublished</span>
    @endif

</div>