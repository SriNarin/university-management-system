<x-filament-panels::page>
    <div class="space-y-6">
        
        {{-- 🎓 NATIVE TOP SELECTION PANEL --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400" style="display: flex; align-items: center; gap: 0.5rem; color: #4f46e5; font-weight: 700;">
                    <span>⚡</span> <span>Select Your Enrolled Course</span>
                </div>
            </x-slot>

            <div class="max-w-md mt-2" style="max-width: 28rem; margin-top: 0.5rem;">
                <select 
                    id="class-select" 
                    wire:model.live="selectedClassId"
                    style="width: 100%; border: 1px solid #d1d5db; padding: 0.6rem 1rem; border-radius: 0.5rem; background-color: #f9fafb; font-size: 0.875rem; font-weight: 600; color: #111827; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
                >
                    <option value="">-- Choose a Class Profile --</option>
                    @foreach($this->enrolledClasses as $class)
                        @php
                            // 🔎 Grab the first schedule record tied to this class item row
                            $firstSchedule = $class->classSchedules->first();
                            
                            // Extract attributes from migration table fields #9
                            $subjectName = $firstSchedule ? $firstSchedule->subject_name_en : null;
                            $teacherName = ($firstSchedule && $firstSchedule->teacher) ? $firstSchedule->teacher->name : null;
                        @endphp
                        
                        <option value="{{ $class->id }}">
                            {{ $class->class_code }} 
                            @if($subjectName) -Subject:  {{ $subjectName }} @endif 
                            @if($teacherName) -Teacher: {{ $teacherName }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
        </x-filament::section>

        @if($selectedClassId)
            {{-- 🌟 FIXED SIDE-BY-SIDE FRAME USING A BULLETPROOF INLINE-FLEX WORKSPACE GRID --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; width: 100%;">

                {{-- 📚 LEFT CONTAINER: MATERIALS HANDOUTS --}}
                <x-filament::section>
                    <x-slot name="heading">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span>📁</span> <span style="font-weight: 700;">Shared Course Materials</span>
                        </div>
                    </x-slot>
                    <x-slot name="description">
                        Download learning resources uploaded by your professor
                    </x-slot>

                    <div style="border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; background: #ffffff; margin-top: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb; color: #374151; font-weight: 700;">
                                    <th style="padding: 1rem;">Topic Heading</th>
                                    <th style="padding: 1rem; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody style="color: #4b5563;">
                                @forelse($this->lessonMaterials as $material)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 1rem;">
                                            {{-- 🌟 UPDATED: Uses lecture_title_topic from the schema design --}}
                                            <div style="font-weight: 700; color: #111827;">{{ $material->lecture_title_topic }}</div>
                                            <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">
                                                📅 {{ $material->created_at?->timezone('Asia/Phnom_Penh')->format('M d, Y h:i A') }}
                                            </div>
                                        </td>
                                        <td style="padding: 1rem; text-align: right; vertical-align: middle;">
                                            {{-- 🌟 UPDATED: Checks and resolves resource_attachment_path fields --}}
                                            @if($material->resource_attachment_path)
                                                <a href="{{ asset('storage/' . $material->resource_attachment_path) }}" download 
                                                   style="display: inline-flex; align-items: center; background-color: #4f46e5; color: white; padding: 0.5rem 0.8rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 700; text-decoration: none; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);"
                                                >
                                                   📥 Download
                                                </a>
                                            @else
                                                <span style="font-size: 0.75rem; color: #9ca3af; font-style: italic;">No attachment</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" style="padding: 2.5rem; text-align: center; color: #9ca3af; font-style: italic;">
                                            No materials have been shared yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-filament::section>

                {{-- 🗓️ RIGHT CONTAINER: ATTENDANCE TRACKER --}}
                <x-filament::section>
                    <x-slot name="heading">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span>📋</span> <span style="font-weight: 700;">Attendance History Log</span>
                        </div>
                    </x-slot>
                    <x-slot name="description">
                        Monitor your presence statuses over consecutive training sessions
                    </x-slot>

                    <div style="border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow: hidden; background: #ffffff; margin-top: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 1px solid #e5e7eb; color: #374151; font-weight: 700;">
                                    <th style="padding: 1rem;">Session Learning Date</th>
                                    <th style="padding: 1rem; text-align: right;">Tracked Status</th>
                                </tr>
                            </thead>
                            <tbody style="color: #4b5563;">
                                @forelse($this->attendanceHistory as $log)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 1rem; font-weight: 600; color: #111827; vertical-align: middle;">
                                            {{ $log->classSchedule?->session_date ?? ($log->created_at ? $log->created_at->format('M d, Y') : 'N/A') }}
                                        </td>
                                        <td style="padding: 1rem; text-align: right; vertical-align: middle;">
                                            @php
                                                $status = strtolower($log->status ?? '');
                                                $bg = '#fef2f2'; $color = '#991b1b'; // absent default
                                                if ($status === 'present') { $bg = '#ecfdf5'; $color = '#065f46'; }
                                                elseif (in_array($status, ['permission', 'excused'])) { $bg = '#fffbeb'; $color = '#92400e'; }
                                            @endphp
                                            <span style="display: inline-block; background-color: #374151 ; color: #4b5563 ; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                                {{ $log->status ?? 'ABSENT' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" style="padding: 2.5rem; text-align: center; color: #9ca3af; font-style: italic;">
                                            No session attendance recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-filament::section>

            </div>
        @else
            {{-- CENTRALIZED EMPTY STATE BOX --}}
            <div style="background: white; padding: 3rem; text-align: center; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.05); border: 1px solid #e5e7eb; max-w: 28rem; margin: 3rem auto 0 auto;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">✨</div>
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #111827;">Welcome to Your Hub Workspace</h3>
                <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem; line-height: 1.5;">
                    Please select an enrolled academic class option from the filter input above to download file items and review active metrics.
                </p>
            </div>
        @endif
    </div>
</x-filament-panels::page>