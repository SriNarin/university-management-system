<div class="w-full flex items-center justify-center" style="display: flex !important; justify-content: center !important; align-items: center !important;">
    
    @if($getRecord() && $getRecord()->is_teacher_timetable_published)
        <a 
            href="{{ route('timetable.print', $getRecord()->id) }}" 
            target="_blank"
            onclick="event.stopPropagation();" 
            title="Print Session Schedule Sheet"
            style="display: inline-flex !important; align-items: center; justify-content: center; gap: 6px; padding: 0 12px; height: 32px; border-radius: 6px; background-color: #ecfdf5; color: #059669; font-size: 12px; font-weight: 700; border: 1px solid #d1fae5; transition: all 0.2s;"
            onmouseover="this.style.backgroundColor='#d1fae5'"
            onmouseout="this.style.backgroundColor='#ecfdf5'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Print Sheet
        </a>
    @else
        <span style="color: #9ca3af; font-size: 12px; font-style: italic;" class="select-none">—</span>
    @endif

</div>