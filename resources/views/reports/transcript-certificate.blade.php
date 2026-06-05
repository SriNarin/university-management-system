<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Academic Transcript - Certificate Record</title>
    <style>
        /* Force CSS page breaks and layout bindings for clean printing output counters */
        @page {
            size: A4 portrait;
            margin: 0; /* Drops browser headers/footers out of execution completely */
        }
        @media print {
            body { 
                margin: 0 !important; 
                padding: 0 !important; 
                background: #fff; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
            .print-btn-container { display: none !important; }
            .certificate-container {
                box-shadow: none !important;
                border: 6px double #1e3a8a !important;
                margin: 0 !important;
                width: 100% !important;
                height: 100vh !important; /* Locks framework scale directly to one sheet height */
                box-sizing: border-box !important;
            }
        }
        
        /* Screen view optimization controls */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            color: #222; 
            background: #f3f4f6; 
            margin: 0; 
            padding: 20px; 
        }
        .certificate-container { 
            max-width: 800px; 
            min-height: 297mm; /* Standard A4 Height boundary anchor context */
            margin: 0 auto; 
            background: #fff; 
            border: 6px double #1e3a8a; 
            padding: 30px 40px; /* Reduced to protect document footer boundaries */
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Keeps the signature panel anchored neatly at the bottom */
        }
        
        /* Structural layout elements */
        .header-section { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1e3a8a; padding-bottom: 12px; }
        .institution-title { font-size: 22px; font-weight: bold; color: #1e3a8a; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; }
        .document-title { font-size: 16px; color: #b91c1c; font-weight: 600; margin-top: 6px; text-transform: uppercase; letter-spacing: 1.5px; }
        
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; background: #f9fafb; padding: 12px 20px; border-radius: 6px; border: 1px solid #e5e7eb; }
        .meta-item { font-size: 13px; line-height: 1.5; }
        .meta-item strong { color: #1e3a8a; }
        
        .grade-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .grade-table th { background: #1e3a8a; color: #fff; font-size: 12px; text-transform: uppercase; padding: 8px 12px; text-align: left; }
        .grade-table td { padding: 8px 12px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
        .grade-table tr:nth-child(even) { background: #f9fafb; }
        
        .summary-box { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .summary-card { background: #1e3a8a; color: #fff; padding: 10px 20px; border-radius: 4px; text-align: right; min-width: 180px; }
        .summary-card h3 { margin: 0; font-size: 12px; font-weight: 400; opacity: 0.9; }
        .summary-card p { margin: 2px 0 0 0; font-size: 22px; font-weight: bold; }
        
        .footer-signatures { display: flex; justify-content: space-between; margin-top: auto; padding-top: 20px; }
        .signature-slot { text-align: center; width: 220px; font-size: 12px; color: #444; }
        .signature-line { border-top: 1px solid #9ca3af; margin-bottom: 6px; margin-top: 40px; }
        
        .print-btn-container { text-align: center; margin-bottom: 15px; }
        .print-btn { background: #10b981; color: white; border: none; padding: 10px 24px; font-size: 14px; font-weight: bold; border-radius: 6px; cursor: pointer; transition: background 0.2s; }
        .print-btn:hover { background: #059669; }
    </style>
</head>
<body>

    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">🖨️ Click to Print Official Transcript Document</button>
    </div>

    <div class="certificate-container">
        <div>
            <div class="header-section">
                <h1 class="institution-title">{{ $schoolClass->academicStructure->department->faculty->name_en ?? 'ROYAL UNIVERSITY OF PHNOM PENH' }}</h1>
                <div class="document-title">Official Academic Transcript Record</div>
            </div>

            <div class="meta-grid">
                <div class="meta-item">
                    <strong>Student Name:</strong> {{ $student->name }}<br>
                    <strong>Email Reference:</strong> {{ $student->email }}<br>
                    <strong>Department:</strong> {{ $schoolClass->academicStructure->department->name_en }}
                </div>
                <div class="meta-item" style="text-align: right;">
                    <strong>Academic Class Code:</strong> {{ $schoolClass->class_code }}<br>
                    <strong>Academic Program Level:</strong> {{ strtoupper($schoolClass->academicStructure->academic_level) }}<br>
                    <strong>Issued Date:</strong> {{ $issueDate }}
                </div>
            </div>

            <table class="grade-table">
                <thead>
                    <tr>
                        <th>Subject Course </th>
                        <th style="text-align: center; width: 140px;">Official Score</th>
                        <th style="text-align: center; width: 110px;">Letter Grade</th>
                        <th style="text-align: center; width: 90px;">GPA Point</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr>
                            <td><strong>{{ $grade->classSchedule->subject_name_en }}</strong> ({{ $grade->classSchedule->subject_code }})</td>
                            <td style="text-align: center; font-weight: bold;">{{ number_format($grade->total_accumulated_score, 2) }}</td>
                            <td style="text-align: center;"><span style="background: #eff6ff; color: #1e40af; padding: 2px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">{{ $grade->final_grade_letter }}</span></td>
                            <td style="text-align: center; font-weight: 500;">{{ number_format($grade->calculated_gpa_point, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #6b7280; padding: 20px; font-size: 13px;">No verified academic marks found for this layout block profile.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="summary-box">
                <div class="summary-card">
                    <h3>Cumulative Score Average (CGPA)</h3>
                    <p>{{ $cgpa }}</p>
                </div>
            </div>
        </div>

        <div class="footer-signatures">
            <div class="signature-slot">
                <div class="signature-line"></div>
                Prepared By (Office Registrar)
            </div>
            <div class="signature-slot">
                <div class="signature-line"></div>
                Verified By (Faculty Manager Head of Department)
            </div>
        </div>
    </div>

</body>
</html>