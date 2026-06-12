<div class="w-full flex items-center justify-center" style="display: flex !important; justify-content: center !important; align-items: center !important;">
    @if($getRecord() && $getRecord()->classSchedule)
        <a 
            href="{{ route('office.transcript.print', [
                'student' => $getRecord()->student_id,
                'class' => $getRecord()->classSchedule->school_class_id
            ]) }}" 
            target="_blank"
            title="View & Print Official Academic Transcript"
            class="inline-flex items-center justify-center gap-2 px-3.5 py-1.5 rounded-md text-xs font-bold tracking-wide border transition-all duration-200 shadow-sm"
            style="
                display: inline-flex !important; 
                align-items: center !important; 
                justify-content: center !important; 
                gap: 8px !important; 
                padding: 6px 14px !important; 
                border-radius: 6px !important; 
                background-color: #4f46e5; 
                color: #ffffff; 
                border: 1px solid #4338ca; 
                text-decoration: none !important; 
                font-size: 12px !important; 
                font-weight: 700 !important; 
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                transition: all 0.2s ease-in-out;
            "
            onmouseover="this.style.backgroundColor='#4338ca'; this.style.borderColor='#3730a3';"
            onmouseout="this.style.backgroundColor='#4f46e5'; this.style.borderColor='#4338ca';"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor" style="width: 15px; height: 15px; display: inline-block;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            
            <span>Generate Transcript</span>
        </a>
    @else
        <span class="text-xs text-gray-400 select-none bg-gray-50 px-2 py-1 rounded border border-gray-200/60 font-mono" style="color: #9ca3af; font-size: 11px; font-family: monospace;">
            Empty Record
        </span>
    @endif
</div>