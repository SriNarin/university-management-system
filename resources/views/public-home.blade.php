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
                    <span class="text-xl">🏛️</span>
                </div>
                <div>
                    <span class="text-xs font-bold tracking-widest text-blue-600 uppercase block">Royal Academic</span>
                    <span class="font-extrabold text-lg tracking-tight text-gray-900">University Matrix</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-x-8 text-sm font-medium text-gray-600">
                <a href="#" class="text-blue-600 font-semibold">Overview</a>
                <a href="#live-metrics" class="hover:text-blue-600 transition-colors">Real-Time Sync</a>
                <a href="#academic-tracks" class="hover:text-blue-600 transition-colors">Academic Tracks</a>
                <a href="#curriculum-section" class="hover:text-blue-600 transition-colors">Daily Curriculums</a>
                <a href="#environment-gallery" class="hover:text-blue-600 transition-colors">Campus Environment</a>
            </nav>

            <button @click="openLogin = true" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                User Account Login →
            </button>
        </div>
    </header>

    <main class="flex-1">
        <section class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6 text-center lg:text-left animate-fade-in">
                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 mb-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse mr-1.5"></span> Systems Active • Academic Year 2026/2027
                </span>
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl leading-tight">
                    Shaping the Future <br>
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Innovation</span> of Higher Ed
                </h1>
                <p class="text-base text-gray-600 sm:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Welcome to our integrated digital campus matrix. This unified gateway securely links students, faculty members, corporate managers, and study offices directly to active course parameters, schedules, and real-time live evaluations.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                    <button @click="openLogin = true" class="rounded-xl bg-gray-900 px-6 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-gray-800 transition-all duration-300">
                        Access Portal Matrix
                    </button>
                    <a href="#live-metrics" class="rounded-xl border border-gray-300 bg-white px-6 py-3.5 text-sm font-semibold text-gray-700 shadow-xs hover:bg-gray-50 transition-colors flex items-center justify-center">
                        View Live Stats Matrix
                    </a>
                </div>
            </div>
            
            <div id="about" class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="group p-6 bg-white rounded-2xl border border-gray-200/80 shadow-xs hover:border-blue-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="h-10 w-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl text-xl mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">🎓</div>
                    <h3 class="font-bold text-gray-900 text-sm">Academic Programs</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">Comprehensive Tracks including Foundation, Bachelor, Master, and PhD parameters.</p>
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
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">Live tracking metrics verifying operational class session attendance behaviors.</p>
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
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mt-1">Faculties & Internal Departments Matrix</h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex items-center gap-x-1.5 rounded-md bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-800 border border-blue-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-ping"></span> Live Integration Streams Enabled
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faculty Module</span>
                            <h3 class="font-bold text-gray-900 text-lg mt-0.5">Faculty of Science & Tech</h3>
                        </div>
                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md border border-blue-100">4 Depts</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>💻 Computer Science</span>
                            <span class="text-gray-400">14 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>📡 Network Infrastructure Engineering</span>
                            <span class="text-gray-400">12 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>🤖 Artificial Intelligence Systems</span>
                            <span class="text-gray-400">10 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>🔒 Cybersecurity Operations</span>
                            <span class="text-gray-400">11 Subjects</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faculty Module</span>
                            <h3 class="font-bold text-gray-900 text-lg mt-0.5">Faculty of Engineering</h3>
                        </div>
                        <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-md border border-indigo-100">3 Depts</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>⚡ Electrical Engineering</span>
                            <span class="text-gray-400">9 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>⚙️ Mechanical & Robotic Systems</span>
                            <span class="text-gray-400">12 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>🏗️ Civil Infrastructure</span>
                            <span class="text-gray-400">8 Subjects</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-300 md:col-span-2 lg:col-span-1">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faculty Module</span>
                            <h3 class="font-bold text-gray-900 text-lg mt-0.5">Faculty of Business Administration</h3>
                        </div>
                        <span class="bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-md border border-purple-100">2 Depts</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex justify-between text-xs font-medium text-gray-600">
                            <span>📈 International Business Matrix</span>
                            <span class="text-gray-400">8 Subjects</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium text-gray-600">
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
                    <button @click="activeTrack = 'foundation'" :class="activeTrack === 'foundation' ? 'bg-white text-blue-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'" class="flex-1 text-xs px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap">Foundation Year</button>
                    <button @click="activeTrack = 'bachelor'" :class="activeTrack === 'bachelor' ? 'bg-white text-blue-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'" class="flex-1 text-xs px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap">Bachelor Track</button>
                    <button @click="activeTrack = 'postgrad'" :class="activeTrack === 'postgrad' ? 'bg-white text-blue-600 shadow-sm font-semibold' : 'text-gray-500 hover:text-gray-900'" class="flex-1 text-xs px-4 py-2.5 rounded-xl transition-all duration-200 whitespace-nowrap">Master & PhD</button>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-10 shadow-xs min-h-[400px] flex items-center">
                
                <div x-show="activeTrack === 'foundation'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95 transform"
                     x-transition:enter-end="opacity-100 scale-100 transform"
                     class="grid md:grid-cols-2 gap-8 items-center w-full">
                    <div class="space-y-4">
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
                        <img class="rounded-2xl object-cover h-72 w-full shadow-md border border-gray-100" src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=800&auto=format&fit=crop&q=80" alt="University Foundation Academic Hub">
                    </div>
                </div>

                <div x-show="activeTrack === 'bachelor'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95 transform"
                     x-transition:enter-end="opacity-100 scale-100 transform"
                     class="grid md:grid-cols-2 gap-8 items-center w-full">
                    <div class="space-y-4">
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
                        <img class="rounded-2xl object-cover h-72 w-full shadow-md border border-gray-100" src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80" alt="Advanced Engineering Tech Suites">
                    </div>
                </div>

                <div x-show="activeTrack === 'postgrad'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95 transform"
                     x-transition:enter-end="opacity-100 scale-100 transform"
                     class="grid md:grid-cols-2 gap-8 items-center w-full">
                    <div class="space-y-4">
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
                        <img class="rounded-2xl object-cover h-72 w-full shadow-md border border-gray-100" src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&auto=format&fit=crop&q=80" alt="Executive Postgrad Infrastructure Labs">
                    </div>
                </div>

            </div>
        </section>

        <section id="curriculum-section" class="bg-gray-100 border-y border-gray-200 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Active Academic Syllabi</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 mt-1">School Curriculums Matrix</h2>
                    <p class="text-sm text-gray-500 mt-2">Explore active course syllabi synchronized daily from the core database registry. Select a specific focus area to read through active credit loads.</p>
                    
                    <div class="mt-6 flex justify-center gap-3">
                        <button @click="curriculumDept = 'cs'" :class="curriculumDept === 'cs' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-xs transition-all duration-200">Computer Science Curriculum</button>
                        <button @click="curriculumDept = 'ne'" :class="curriculumDept === 'ne' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'" class="px-4 py-2 rounded-xl text-xs font-semibold shadow-xs transition-all duration-200">Network Engineering</button>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden p-6 sm:p-8">
                    <div x-show="curriculumDept === 'cs'" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                        <div class="border-b border-gray-100 pb-4 flex justify-between items-center">
                            <h4 class="font-bold text-gray-900 text-base">Department Syllabus Block: Computer Science (CS)</h4>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-sm">Updated Today</span>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Algorithms & Data Structures</span>
                                    <span class="text-blue-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Comprehensive sorting trees, arrays, complexity calculations, and database mapping frameworks.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Advanced Web Development (TALL Stack)</span>
                                    <span class="text-blue-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Building modern reactive systems using Laravel, Tailwind CSS, Livewire, and Alpine.js configurations.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Relational Database Architecture</span>
                                    <span class="text-blue-600">3 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Deep-dive structural SQL schema query optimization, multi-pivot tables, and index keys management.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
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
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Cisco Enterprise Architecture Routing</span>
                                    <span class="text-indigo-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Deploying robust, scale-ready corporate packet filters, core gateways, and subnetwork partitions.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Cloud Systems & Microservices Architecture</span>
                                    <span class="text-indigo-600">3 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Configuring modular virtual server storage containers, container pipelines, and balance engines.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                <div class="flex justify-between font-bold text-sm text-gray-900">
                                    <span>Cybersecurity Cryptography Operations</span>
                                    <span class="text-indigo-600">4 Credits</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Implementing deep asymmetric validation layers, hash configurations, and internal security logs.</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
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
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-xs" @click="openLogin = false"></div>

        <div class="fixed inset-0 overflow-hidden flex justify-end">
            <div class="w-full max-w-md bg-white h-full shadow-2xl p-8 flex flex-col justify-between"
                 x-show="openLogin" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="translate-x-full">
                
                <div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🛡️</span>
                            <h2 class="text-lg font-bold text-gray-900">Sign in to Campus Portal</h2>
                        </div>
                        <button @click="openLogin = false" class="text-gray-400 hover:text-gray-600 text-2xl font-light focus:outline-hidden">&times;</button>
                    </div>

                    @if ($errors->any())
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600 font-medium">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('unified.login') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">Institutional Email</label>
                            <input type="email" name="email" required value="{{ old('email') }}" class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm shadow-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-hidden" placeholder="username@university.edu">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">Account Password</label>
                            <input type="password" name="password" required class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm shadow-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-hidden" placeholder="••••••••">
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-x-2 text-xs text-gray-600 select-none">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"> Keep me signed in
                            </label>
                        </div>

                        <button type="submit" class="w-full rounded-xl bg-blue-600 py-3 text-sm font-bold text-white shadow-sm hover:bg-blue-500 transition-all mt-4">
                            Log In to the University Management System 
                        </button>
                    </form>
                </div>

                <div class="text-center text-[11px] text-gray-400 border-t border-gray-100 pt-4">
                    Secured University Authentication Engine Gateway.
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