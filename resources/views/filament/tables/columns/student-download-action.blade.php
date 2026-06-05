<div class="w-full flex items-center justify-center" style="display: flex !important; justify-content: center !important; align-items: center !important;">
    @if($getRecord() && $getRecord()->classSchedule)
        <a 
            href="{{ route('office.transcript.print', [
                'student' => $getRecord()->student_id,
                'class' => $getRecord()->classSchedule->school_class_id
            ]) }}" 
            target="_blank"
            title="View & Print Official Transcript"
            style="display: inline-flex !important; align-items: center; justify-content: center; gap: 6px; padding: 6px 12px; border-radius: 6px; background-color: #eff6ff; color: #2563eb; text-decoration: none; font-size: 13px; font-weight: 600; transition: all 0.2s;"
            onmouseover="this.style.backgroundColor='#dbeafe'"
            onmouseout="this.style.backgroundColor='#eff6ff'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Get Certificate</span>
        </a>
    @else
        <span style="color: #9ca3af; font-size: 12px;">—</span>
    @endif
</div>