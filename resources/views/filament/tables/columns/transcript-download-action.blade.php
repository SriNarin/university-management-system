<div class="flex items-center justify-center gap-3 px-2 py-1">
    
    <form action="{{ route('timetable.toggle-status', $getRecord()->id) }}" 
          method="POST" 
          class="inline-flex items-center"
          onclick="event.stopPropagation();">
        @csrf
        
        @if($getRecord()->is_timetable_published)
            <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition shadow-sm">
                <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                🖨️Click to Print and Download Timetables
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
           title="Download Academic Timetable"
           style="display: inline-flex !important; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background-color: #ecfdf5; color: #059669; transition: all 0.2s;"
           onmouseover="this.style.backgroundColor='#d1fae5'"
           onmouseout="this.style.backgroundColor='#ecfdf5'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px; display: block;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
        </a>
    @else
        <span class="text-[11px] text-gray-400 font-mono tracking-wider select-none bg-gray-50 px-1.5 py-0.5 rounded border border-gray-200/50">Unpublished</span>
    @endif

</div>