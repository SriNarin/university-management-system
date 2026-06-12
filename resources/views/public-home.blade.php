<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-gray-900 antialiased" 
      x-data="{ 
          openLogin: false, 
          activeTrack: 'bachelor',
          curriculumDept: 'cs',
          statsSearch: ''
      }">

    <header class="bg-white/90 backdrop-blur-md border-b border-gray-200 sticky top-0 z-40 shadow-xs transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-x-3.5 group">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20 transform group-hover:rotate-6 transition-transform duration-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
              <a href="#" class="group block focus:outline-hidden">
                    <div>
                        <span class="text-xs font-bold tracking-widest text-blue-600 uppercase block transform group-hover:translate-x-0.5 group-hover:tracking-[0.2em] transition-all duration-300 ease-out">
                            Royal Academic
                        </span>
                        
                        <span class="inline-block font-extrabold text-lg tracking-tight text-slate-900 hover:text-blue-950 group-active:scale-[0.98] transition-all duration-200 ease-out mt-0.5">
                            University System
                        </span>
                    </div>
                </a>
            </div>
            
          <nav class="hidden lg:flex items-center gap-x-8 text-sm font-semibold">
                <a href="#" class="relative py-1 text-blue-600 after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-blue-600 after:rounded-full">
                    Overview
                </a>
                
                <a href="#live-metrics" class="group relative py-1 text-slate-600 hover:text-slate-900 transition-colors duration-200">
                    Real-Time Sync
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 bg-blue-600 rounded-full transition-all duration-300 transform -translate-x-1/2 group-hover:w-full"></span>
                </a>

                <a href="#academic-tracks" class="group relative py-1 text-slate-600 hover:text-slate-900 transition-colors duration-200">
                    Academic Tracks
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 bg-blue-600 rounded-full transition-all duration-300 transform -translate-x-1/2 group-hover:w-full"></span>
                </a>

                <a href="#curriculum-section" class="group relative py-1 text-slate-600 hover:text-slate-900 transition-colors duration-200">
                    Daily Curriculums
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 bg-blue-600 rounded-full transition-all duration-300 transform -translate-x-1/2 group-hover:w-full"></span>
                </a>

                <a href="#environment-gallery" class="group relative py-1 text-slate-600 hover:text-slate-900 transition-colors duration-200">
                    Campus Environment
                    <span class="absolute bottom-0 left-1/2 h-0.5 w-0 bg-blue-600 rounded-full transition-all duration-300 transform -translate-x-1/2 group-hover:w-full"></span>
                </a>
            </nav>

<button @click="openLogin = true" 
    class="group relative inline-flex items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-blue-600/10 hover:bg-blue-500 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-600/20 active:scale-[0.97] active:translate-y-0 transition-all duration-200 focus:outline-hidden focus:ring-4 focus:ring-blue-600/20">
    
    <span>User Account Login</span>
    
    <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7-7M21 12H3" />
    </svg>
</button>
        </div>
    </header>

    <main class="flex-1">
        <section class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6 text-center lg:text-left animate-fade-in">
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 mb-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse mr-1.5"></span> Systems Active • Academic Year 2026/2027
                </span>
                <h1 class="bg-gradient-to-r from-gray-900 via-blue-600 to-gray-900 bg-clip-text text-transparent text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl leading-tight  ">
                    Shaping the Future <br>
                   <span class="bg-gradient-to-r from-blue-500 via-purple-600 to-indigo-600 bg-clip-text text-transparent">Innovation</span> of Higher Education
                </h1>
                <p class="text-base text-gray-600 sm:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Welcome to our integrated digital University Management System, where the power of technology meets the needs of campus and colleges . This unified gateway securely links students, faculty members, corporate managers, and study offices directly to active courses, majors, schedules, and real-time live evaluations.
                </p>
               <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
    <button @click="openLogin = true" 
        class="group relative rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-md shadow-slate-950/10 hover:bg-slate-900 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-950/20 active:scale-[0.97] active:translate-y-0 transition-all duration-200 focus:outline-hidden focus:ring-4 focus:ring-slate-950/10">
        <span class="flex items-center gap-1.5">
            Get Started
            <svg class="h-4 w-4 transform group-hover:translate-x-0.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7-7M21 12H3" />
            </svg>
        </span>
    </button>

    <a href="#live-metrics" 
        class="rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-xs hover:bg-slate-50 hover:text-slate-900 hover:border-slate-400 hover:-translate-y-0.5 hover:shadow-md active:scale-[0.97] active:translate-y-0 transition-all duration-200 flex items-center justify-center focus:outline-hidden focus:ring-4 focus:ring-slate-100">
        School Information
    </a>
</div>
            </div>
            
            <div id="about" class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="group p-6 bg-white rounded-2xl border border-gray-200/80 shadow-xs hover:border-blue-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="h-10 w-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl text-xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">🎓</div>
                    <h3 class="font-bold text-gray-900 text-sm">Academic Programs</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">Comprehensive Tracks including Foundation, Bachelor, Master, and PhD study programs.</p>
                </div>

                <div class="group p-6 bg-white rounded-2xl border border-gray-200/80 shadow-xs hover:border-indigo-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 sm:mt-6">
                    <div class="h-10 w-10 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl text-xl mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">📅</div>
                    <h3 class="font-bold text-gray-900 text-sm">Dynamic Timetables</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">Fully mapped chronological weekly schedules distributed across rooms automatically.</p>
                </div>

                <div class="group p-6 bg-white rounded-2xl border border-gray-200/80 shadow-xs hover:border-emerald-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="h-10 w-10 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl text-xl mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">📝</div>
                    <h3 class="font-bold text-gray-900 text-sm">Live Track Assessments</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">Continuous grading workflows with immediate secure teacher evaluations and feedback.</p>
                </div>

                <div class="group p-6 bg-white rounded-2xl border border-gray-200/80 shadow-xs hover:border-amber-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 sm:mt-6">
                    <div class="h-10 w-10 flex items-center justify-center bg-amber-50 text-amber-600 rounded-xl text-xl mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">✅</div>
                    <h3 class="font-bold text-gray-900 text-sm">Attendance Logs</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">Live tracking students attendances verifying operational class session attendance behaviors.</p>
                </div>
            </div>
        </section>

        <section id="live-metrics" class="bg-gray-900 text-white py-12 border-y border-gray-800 shadow-inner">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="transform hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl lg:text-4xl font-extrabold text-blue-400">12,450+</div>
                    <div class="text-[11px] uppercase font-bold tracking-widest text-gray-400 mt-1">Live Update Students</div>
                </div>
                <div class="transform hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl lg:text-4xl font-extrabold text-indigo-400">5 Roles</div>
                    <div class="text-[11px] uppercase font-bold tracking-widest text-gray-400 mt-1">Unified Dashboards</div>
                </div>
                <div class="transform hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl lg:text-4xl font-extrabold text-emerald-400">380+</div>
                    <div class="text-[11px] uppercase font-bold tracking-widest text-gray-400 mt-1">Active Faculty Teachers</div>
                </div>
                <div class="transform hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl lg:text-4xl font-extrabold text-amber-400">42 Classes</div>
                    <div class="text-[11px] uppercase font-bold tracking-widest text-gray-400 mt-1">Real-Time Rooms Active</div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 bg-linear-to-b from-transparent to-gray-100/50">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Live Structural Relational Database</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mt-1">Faculties & Internal Departments Curriculums</h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-800 border border-blue-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-ping"></span> Live Integration Streams Enabled
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-300 hover:-translate-y-3">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4  transition-all duration-3">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faculty Module</span>
                            <h3 class="font-bold text-gray-900 text-lg mt-0.5">Faculty of Science & Tech</h3>
                        </div>
                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md border border-blue-100">4 Depts</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800">
                            <span>💻 Computer Science</span>
                            <span class="text-gray-400">14 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800">
                            <span>📡 Network Infrastructure Engineering</span>
                            <span class="text-gray-400">12 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800 ">
                            <span>🤖 Artificial Intelligence Systems</span>
                            <span class="text-gray-400">10 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800 ">
                            <span>🔒 Cybersecurity Operations</span>
                            <span class="text-gray-400">11 Subjects</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-300 hover:-translate-y-3">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faculty Module</span>
                            <h3 class="font-bold text-gray-900 text-lg mt-0.5">Faculty of Engineering</h3>
                        </div>
                        <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-md border border-indigo-100">3 Depts</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800 ">
                            <span>⚡ Electrical Engineering</span>
                            <span class="text-gray-400">9 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800 ">
                            <span>⚙️ Mechanical & Robotic Systems</span>
                            <span class="text-gray-400">12 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800 ">
                            <span>🏗️ Civil Infrastructure</span>
                            <span class="text-gray-400">8 Subjects</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-300 md:col-span-2 lg:col-span-1 hover:-translate-y-3">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faculty Module</span>
                            <h3 class="font-bold text-gray-900 text-lg mt-0.5">Faculty of Business Administration</h3>
                        </div>
                        <span class="bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-md border border-purple-100">2 Depts</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800 ">
                            <span>📈 International Business Matrix</span>
                            <span class="text-gray-400">8 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600 hover:text-blue-800 ">
                            <span>📊 Financial Technology Accounting</span>
                            <span class="text-gray-400">10 Subjects</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="academic-tracks" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-gray-200">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Institutional Architecture</span>
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl mt-2">Comprehensive Level Mapping</h2>
                <p class="text-gray-500 mt-3 text-sm sm:text-base">Click through the structural tiers below to evaluate how our relational models process dynamic generation rules.</p>
                
                <div class="mt-6 inline-flex p-1.5 bg-gray-100 rounded-2xl border border-gray-200 w-full max-w-xl overflow-x-auto">
                    <button @click="activeTrack = 'foundation'" :class="activeTrack === 'foundation' ? 'bg-white text-blue-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'" class="flex-1 text-xs px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap hover:translate-x-1">Foundation Year</button>
                    <button @click="activeTrack = 'bachelor'" :class="activeTrack === 'bachelor' ? 'bg-white text-blue-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'" class="flex-1 text-xs px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap hover:translate-x-1">Bachelor Track</button>
                    <button @click="activeTrack = 'postgrad'" :class="activeTrack === 'postgrad' ? 'bg-white text-blue-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'" class="flex-1 text-xs px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap hover:translate-x-1">Master & PhD</button>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-10 shadow-xs min-h-[400px] flex items-center">
                
                <div x-show="activeTrack === 'foundation'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95 transform"
                     x-transition:enter-end="opacity-100 scale-100 transform"
                     class="grid md:grid-cols-2 gap-8 items-center w-full">
                    <div class="space-y-4 hover:translate-x-2">
                        <div class="h-8 w-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">01</div>
                        <h3 class="text-xl font-bold text-gray-900">General Core Preparation</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Every incoming generation completes intensive core prerequisites across orientation paths before routing into localized department specializations.
                        </p>
                        <ul class="space-y-2 text-xs text-gray-500 font-medium">
                            <li class="flex items-center gap-2">🔹 System-wide Automated Generation Splits</li>
                            <li class="flex items-center gap-2">🔹 Standardized Room Allocation Matrices</li>
                        </ul>
                    </div>
                    <div>
                        <img class="rounded-2xl object-cover h-72 w-full shadow-md border border-gray-100 hover:translate-x-2" src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&auto=format&fit=crop&q=80" alt="University Foundation Academic Hub">
                    </div>
                </div>

                <div x-show="activeTrack === 'bachelor'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95 transform"
                     x-transition:enter-end="opacity-100 scale-100 transform"
                     class="grid md:grid-cols-2 gap-8 items-center w-full">
                    <div class="space-y-4 hover:translate-x-2">
                        <div class="h-8 w-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-sm font-bold">02</div>
                        <h3 class="text-xl font-bold text-gray-900">Advanced Engineering & Tech Degrees</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Specialized full-stack architecture modules, relational SQL database structures, and high-performance Cisco network topology modeling modules.
                        </p>
                        <ul class="space-y-2 text-xs text-gray-500 font-medium">
                            <li class="flex items-center gap-2">🔹 Filament TALL-Stack Administration Dashboards</li>
                            <li class="flex items-center gap-2">🔹 Multi-Role Pivot Level Security Matrix Configuration</li>
                        </ul>
                    </div>
                    <div>
                        <img class="rounded-2xl object-cover h-72 w-full shadow-md border border-gray-100 hover:translate-x-2" src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80" alt="Advanced Engineering Tech Suites">
                    </div>
                </div>

                <div x-show="activeTrack === 'postgrad'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95 transform"
                     x-transition:enter-end="opacity-100 scale-100 transform"
                     class="grid md:grid-cols-2 gap-8 items-center w-full">
                    <div class="space-y-4 hover:translate-x-2">
                        <div class="h-8 w-8 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center text-sm font-bold">03</div>
                        <h3 class="text-xl font-bold text-gray-900">Executive Master & PhD Research Labs</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Deep analysis fields investigating predictive tracking scripts, integrated video models, and distributed micro-server infrastructure systems.
                        </p>
                        <ul class="space-y-2 text-xs text-gray-500 font-medium">
                            <li class="flex items-center gap-2">🔹 Live Academic Faculty Supervisor Assignments</li>
                            <li class="flex items-center gap-2">🔹 Continuous External Assessment Workflows</li>
                        </ul>
                    </div>
                    <div>
                        <img class="rounded-2xl object-cover h-72 w-full shadow-md border border-gray-100 hover:translate-x-2" src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&auto=format&fit=crop&q=80" alt="Executive Postgrad Infrastructure Labs">
                    </div>
                </div>

            </div>
        </section>

        <section id="curriculum-section" class="bg-gray-100 border-y border-gray-200 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Active Academic Syllabus</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-1">School Curriculums and Syllabus</h2>
                    <p class="text-sm text-gray-500 mt-2">Explore active course syllabi synchronized daily from the core database registry. Select a specific focus area to read through active credit loads.</p>
                    
                    <div class="mt-6 flex justify-center gap-3">
                        <button @click="curriculumDept = 'cs'" :class="curriculumDept === 'cs' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-xs transition-all duration-200 hover:translate-x-1 active:translate-y-2">Computer Science Curriculum</button>
                        <button @click="curriculumDept = 'ne'" :class="curriculumDept === 'ne' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-xs transition-all duration-200 hover:translate-x-1 active:translate-y-2">Network Engineering</button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden p-6 sm:p-8 hover:translate-y-1">
                    <div x-show="curriculumDept === 'cs'" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                        <div class="border-b border-gray-100 pb-4 flex justify-between items-center">
                            <h4 class="font-bold text-gray-900 text-base">Department Syllabus Block: Computer Science (CS)</h4>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-sm">Updated Today</span>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900 ">
                                    <span>Algorithms & Data Structures</span>
                                    <span class="text-blue-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Comprehensive sorting trees, arrays, complexity calculations, and database mapping frameworks.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Advanced Web Development (TALL Stack)</span>
                                    <span class="text-blue-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Building modern reactive systems using Laravel, Tailwind CSS, Livewire, and Alpine.js configurations.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Relational Database Architecture</span>
                                    <span class="text-blue-600">3 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Deep-dive structural SQL schema query optimization, multi-pivot tables, and index keys management.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Machine Learning Engineering</span>
                                    <span class="text-blue-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Data predictive vectors, tensor operations, matrix calculation loops, and automation models.</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="curriculumDept === 'ne'" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                        <div class="border-b border-gray-100 pb-4 flex justify-between items-center">
                            <h4 class="font-bold text-gray-900 text-base">Department Syllabus Block: Network Infrastructure (NE)</h4>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-sm">Updated Today</span>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Cisco Enterprise Architecture Routing</span>
                                    <span class="text-indigo-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Deploying robust, scale-ready corporate packet filters, core gateways, and subnetwork partitions.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Cloud Systems & Microservices Architecture</span>
                                    <span class="text-indigo-600">3 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Configuring modular virtual server storage containers, container pipelines, and balance engines.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Cybersecurity Cryptography Operations</span>
                                    <span class="text-indigo-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Implementing deep asymmetric validation layers, hash configurations, and internal security logs.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:translate-x-2 hover:translate-y-1">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Wireless Telecom Systems Matrix</span>
                                    <span class="text-indigo-600">3 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Analysis vectors covering regional radio spectrum properties, high frequency channels, and cell transfers.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="environment-gallery" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div class="w-full text-center">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Campus Infrastructure</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mt-1">Campus Environment & Facilities</h2>
                    <p class="text-sm text-gray-500 mt-2 max-w-xl mx-auto">Explore the high-fidelity facilities, research ecosystems, and learning spaces powering our tech matrix.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="relative overflow-hidden h-52 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1562774053-701939374585?w=800&auto=format&fit=crop&q=80" 
                             alt="Digital Research Library" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 bg-blue-600/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">University Campus</span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-center items-center text-center">
                        <h3 class="font-bold text-gray-900 text-sm group-hover:text-blue-600 transition-colors">Wide Campus Academic comfortability </h3>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed max-w-xs">Comfortable learning spaces, ample office space, modern Lab Rooms and library.</p>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="relative overflow-hidden h-52 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1581092921461-eab62e97a780?w=800&auto=format&fit=crop&q=80" 
                             alt="Network Infrastructure Engineering Suite" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 bg-indigo-600/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Modern Lab Room </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-center items-center text-center">
                        <h3 class="font-bold text-gray-900 text-sm group-hover:text-indigo-600 transition-colors"> Comprehensive Lab Practice Facilities </h3>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed max-w-xs">Practice facilities with modern equipments in laboratory rooms for hands-on learning and experimentation.</p>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 sm:col-span-2 lg:col-span-1 flex flex-col">
                    <div class="relative overflow-hidden h-52 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&q=80&w=600" 
                             alt="Collaborative Smart Auditorium" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 right-3 bg-emerald-600/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">Lecture Matrix</span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-center items-center text-center">
                        <h3 class="font-bold text-gray-900 text-sm group-hover:text-emerald-600 transition-colors">Comfortable Classrooms and Lecture Halls</h3>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed max-w-xs">Wide range of classrooms and lecture halls for all students comprehensive learning and scalability gaining knowledges </p>
                    </div>
                </div>
            </div>
        </section>
    </main>


<div class="relative z-50" x-show="openLogin" x-transition.opacity style="display: none;">
    <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-2xl transition-opacity" @click="openLogin = false"></div>

    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6 lg:p-8">
        
        <div class="relative w-full max-w-5xl aspect-video grid grid-cols-1 md:grid-cols-12 transform overflow-hidden rounded-[32px] bg-white/90 shadow-[0_50px_100px_-20px_rgba(15,23,42,0.18),0_0_1px_rgba(15,23,42,0.25)] border border-white transition-all"
             x-show="openLogin" 
             x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-600 transform" 
             x-transition:enter-start="opacity-0 scale-95 translate-y-8" 
             x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-400 transform" 
             x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="absolute right-6 top-6 z-50">
                <button @click="openLogin = false" class="rounded-full bg-slate-100/50 p-2 text-slate-500 hover:bg-slate-900 hover:text-white transition-all duration-200 shadow-xs focus:outline-hidden">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="relative md:col-span-5 bg-linear-to-br from-slate-900 via-slate-950 to-blue-950 p-8 sm:p-12 flex flex-col justify-between overflow-hidden group">
                <div class="absolute -bottom-20 -left-20 h-72 w-72 bg-blue-500/15 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/20 transition-colors duration-700"></div>
                <div class="absolute -top-20 -right-20 h-72 w-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-md text-white border border-white/10 shadow-xl">
                        <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="2.3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-blue-500 tracking-tight leading-none">Royal Academic</h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1.5">University System Dashboard</p>
                    </div>
                </div>

                <div class="relative z-10 my-auto pt-12 pb-8">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-blue-300 tracking-tight leading-[1.15] ">
                        Shaping the Higher Education of <br>
                        <span class="bg-gradient-to-r from-blue-500 via-purple-600 to-indigo-600 bg-clip-text text-transparent bg-clip-text text-transparent"> Royal Academy University.</span>
                    </h1>
                    <p class="mt-4 text-sm text-slate-400 font-medium leading-relaxed max-w-xs">
                        Welcome to your university system dashboard account showing integrated digital academic interface. Connect seamlessly with your role-specific features, administrative features, and real-time live evaluations.
                    </p>
                </div>

                <div class="relative z-10 pt-4 border-t border-white/5">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500">
                        System Environment Status: <span class="text-emerald-400">Operational Active</span>
                    </p>
                </div>
            </div>

            <div class="md:col-span-7 p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white">
                <div>
                    <div class="border-b border-slate-100 pb-5">
                        <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Sign In to Account Dashboard</h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">Please authorize your credentials to access system features.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-5 p-3.5 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-2.5 text-xs text-red-600 font-medium animate-bounce">
                            <svg class="h-4 w-4 shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <form action="{{ route('unified.login') }}" method="POST" class="mt-6 space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Account Identity Email</label>
                            <div class="relative mt-2">
                                <input type="email" name="email" required value="{{ old('email') }}" autocomplete="email"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3.5 text-sm text-slate-900 transition-all duration-200 focus:bg-white focus:border-slate-950 focus:ring-4 focus:ring-slate-950/5 focus:outline-hidden placeholder:text-slate-400 font-medium" 
                                    placeholder="username@university.edu.kh">
                            </div>
                        </div>

                        <div>
                          <div x-data="{ showPassword: false }">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Secure Password Access</label>
                            
                            <div class="relative mt-2">
                                <input :type="showPassword ? 'text' : 'password'" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-4 pr-12 py-3.5 text-sm text-slate-900 transition-all duration-200 focus:bg-white focus:border-slate-950 focus:ring-4 focus:ring-slate-950/5 focus:outline-hidden placeholder:text-slate-400 font-medium" 
                                    placeholder="••••••••">

                                <button type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center justify-center px-3.5 text-slate-400 hover:text-slate-600 active:scale-95 transition-all duration-150 focus:outline-hidden"
                                    title="Toggle Password Visibility">
                                    
                                    <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644M21.398 11.68a1.012 1.012 0 010 .644M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>

                                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" x-cloak>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-3 text-xs font-medium text-slate-500 cursor-pointer select-none group">
                                <input type="checkbox" name="remember" class="h-4 w-4 rounded-md border-slate-300 bg-white text-slate-900 transition focus:ring-slate-950/20 focus:ring-offset-0 ring-offset-transparent checked:border-slate-950 checked:bg-slate-950"> 
                                <span class="group-hover:text-slate-900 transition-colors">Keep me signed in</span>
                            </label>
                        </div>

                        <button type="submit" class="group relative flex w-full justify-center rounded-xl bg-blue-700 px-4 py-4 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 hover:bg-green-700 active:scale-[0.98] transition-all duration-150 focus:outline-hidden mt-2">
                            <span class="flex items-center gap-2 tracking-wide font-medium">
                                Authenticate Access Account
                                <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7-7M21 12H3" />
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>

                <div class="text-center md:text-left text-[9px] font-bold uppercase tracking-widest text-slate-400 border-t border-slate-100 pt-5 mt-8">
                    Secured University Authentication Engine Gateway
                </div>
            </div>

        </div>
    </div>
</div>
  

    <footer class="bg-gray-900 border-t border-gray-800 text-gray-500 py-8 text-center text-xs">
        <p>&copy; 2026 Royal University Management System Infrastructure. All Rights Reserved.</p>
    </footer>

    @if($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', () => { 
                setTimeout(() => { 
                    document.querySelector('[x-data]').__x.$data.openLogin = true; 
                }, 100); 
            });
        </script>
    @endif
</body>
</html>