<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('student.dashboard') }}"
                        class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
                <h1 class="text-3xl font-bold text-slate-900">Room Application</h1>
                <p class="mt-2 text-slate-600">Submit your application to be allocated a hostel room for
                    <strong>{{ $academicYear }}</strong>.
                </p>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-3 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            {{-- Pending Application Banner --}}
            @if ($pendingApplication)
                <div class="mb-8 bg-amber-50 border border-amber-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-base font-semibold text-amber-800">Application Pending Review</h3>
                            <p class="mt-1 text-sm text-amber-700">
                                You submitted an application on
                                <strong>{{ $pendingApplication->created_at->format('d M Y') }}</strong>. It is
                                currently under review by the administration. You will be notified once it is processed.
                            </p>
                            <form method="POST"
                                action="{{ route('student.room-application.cancel', $pendingApplication->id) }}"
                                class="mt-4" onsubmit="return confirm('Are you sure you want to cancel this application?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-100 border border-red-200 rounded-md hover:bg-red-200 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Application Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900">Application Form</h2>
                            <p class="text-sm text-gray-500 mt-1">Fill in your preferences below. All fields are
                                optional — if you have no preference, simply leave them blank.</p>
                        </div>

                        @if (!$pendingApplication)
                            <form method="POST" action="{{ route('student.room-application.store') }}"
                                class="p-6 space-y-6">
                                @csrf

                                {{-- Preferred Hostel --}}
                                @if ($hostels->isNotEmpty())
                                    <div>
                                        <label for="preferred_hostel" class="block text-sm font-medium text-gray-700 mb-1">
                                            Preferred Hostel
                                            <span class="text-gray-400 font-normal ml-1">(optional)</span>
                                        </label>
                                        <select name="preferred_hostel" id="preferred_hostel"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">No preference</option>
                                            @foreach ($hostels as $hostel)
                                                <option value="{{ $hostel->name }}" {{ old('preferred_hostel') == $hostel->name ? 'selected' : '' }}>
                                                    {{ $hostel->name }}
                                                    ({{ ucfirst($hostel->gender) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('preferred_hostel')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                {{-- Room Type --}}
                                <div>
                                    <label for="room_type" class="block text-sm font-medium text-gray-700 mb-1">
                                        Preferred Room Type
                                        <span class="text-gray-400 font-normal ml-1">(optional)</span>
                                    </label>
                                    <select name="room_type" id="room_type"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <option value="">No preference</option>
                                        <option value="single" {{ old('room_type') == 'single' ? 'selected' : '' }}>
                                            Single (1 person)</option>
                                        <option value="double" {{ old('room_type') == 'double' ? 'selected' : '' }}>
                                            Double (2 persons)</option>
                                        <option value="triple" {{ old('room_type') == 'triple' ? 'selected' : '' }}>
                                            Triple (3 persons)</option>
                                        <option value="quad" {{ old('room_type') == 'quad' ? 'selected' : '' }}>Quad
                                            (4 persons)</option>
                                    </select>
                                    @error('room_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Special Needs --}}
                                <div>
                                    <label for="special_needs" class="block text-sm font-medium text-gray-700 mb-1">
                                        Special Needs / Accessibility Requirements
                                        <span class="text-gray-400 font-normal ml-1">(optional)</span>
                                    </label>
                                    <input type="text" name="special_needs" id="special_needs"
                                        value="{{ old('special_needs') }}"
                                        placeholder="e.g. ground floor access, wheelchair accessible…"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    @error('special_needs')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Additional Notes --}}
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Additional Notes / Motivation
                                        <span class="text-gray-400 font-normal ml-1">(optional)</span>
                                    </label>
                                    <textarea name="notes" id="notes" rows="4"
                                        placeholder="Anything else you'd like the administration to know about your accommodation needs…"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Submit --}}
                                <div class="pt-2">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Submit Application
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="p-6 text-center text-sm text-gray-500">
                                <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                The form is disabled while your application is pending. Cancel your current application
                                above to submit a new one.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar: Student info + Application History --}}
                <div class="space-y-6">

                    {{-- Student Info Card (Premium Redesign V3) --}}
                    <div class="bg-white overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-[2.5rem] border border-gray-100/50 mb-8 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(8,_112,_184,_0.1)] group/card">
                        {{-- Card Header with Dynamic Mesh Gradient & Glass Avatar --}}
                        <div class="p-8 bg-slate-900 relative overflow-hidden">
                            {{-- Mesh Gradient Orbs --}}
                            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-80" style="pointer-events: none;">
                                <div class="absolute rounded-full" style="top: -2.5rem; right: -2.5rem; width: 10rem; height: 10rem; background: #4f46e5; filter: blur(80px); opacity: 0.6;"></div>
                                <div class="absolute rounded-full" style="bottom: -2.5rem; left: -2.5rem; width: 10rem; height: 10rem; background: #3b82f6; filter: blur(80px); opacity: 0.6;"></div>
                                <div class="absolute rounded-full" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 12rem; height: 12rem; background: rgba(168, 85, 247, 0.3); filter: blur(100px);"></div>
                            </div>
                            
                            <div class="relative flex flex-col items-center text-center">
                                @php
                                    $names = explode(' ', $student->full_name);
                                    $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
                                @endphp
                                {{-- Ultra-Glass Avatar --}}
                                <div class="w-20 h-20 bg-white/10 rounded-3xl rotate-3 flex items-center justify-center text-2xl font-black text-white border border-white/30 shadow-2xl mb-5 transform transition-transform duration-500 group-hover/card:rotate-0 group-hover/card:scale-110" style="backdrop-filter: blur(24px) saturate(2);">
                                    <span class="-rotate-3 group-hover/card:rotate-0 transition-transform duration-500">{{ $initials }}</span>
                                </div>
                                
                                <h3 class="text-xl font-extrabold text-white tracking-tight leading-tight mb-1">{{ $student->full_name }}</h3>
                                <div class="inline-flex items-center px-4 py-1 rounded-full bg-white/10 border border-white/20 text-[10px] font-bold text-blue-100 uppercase tracking-[0.2em]" style="backdrop-filter: blur(12px);">
                                    ID: {{ $student->student_id }}
                                </div>
                            </div>
                        </div>

                        {{-- Card Body: Interactive Detail Rows --}}
                        <div class="p-6 space-y-2">
                            {{-- Item Row: Gender --}}
                            <div class="p-3 rounded-2xl transition-all duration-300 hover:bg-slate-50 flex items-center space-x-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100 group-hover/item:scale-110 group-hover/item:bg-blue-600 group-hover/item:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[9px] uppercase tracking-widest text-slate-400 font-black mb-0.5">Gender Identity</p>
                                    <p class="text-sm font-bold text-slate-700">{{ ucfirst($student->gender) }}</p>
                                </div>
                            </div>

                            {{-- Item Row: Department --}}
                            <div class="p-3 rounded-2xl transition-all duration-300 hover:bg-slate-50 flex items-center space-x-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100 group-hover/item:scale-110 group-hover/item:bg-indigo-600 group-hover/item:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[9px] uppercase tracking-widest text-slate-400 font-black mb-0.5">Faculty / Dept</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $student->department }}</p>
                                </div>
                            </div>

                            {{-- Item Row: Academic Level --}}
                            <div class="p-3 rounded-2xl transition-all duration-300 hover:bg-slate-50 flex items-center space-x-4 group/item">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm border border-amber-100 group-hover/item:scale-110 group-hover/item:bg-amber-600 group-hover/item:text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[9px] uppercase tracking-widest text-slate-400 font-black mb-0.5">Academic Level</p>
                                    <p class="text-sm font-bold text-slate-700 group-hover/card:text-blue-600 transition-colors duration-500">{{ ucfirst($student->study_level) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Subtle Footer Accent --}}
                        <div class="h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 opacity-20"></div>
                    {{-- Application History (Clean Redesign) --}}
                    <div class="bg-white overflow-hidden shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-3xl border border-gray-100 mt-8">
                        <div class="p-6 border-b border-gray-50 bg-slate-50/50">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Application History</h3>
                        </div>
                        <div class="p-6">
                            @if ($applications->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach ($applications as $application)
                                        <div class="relative pl-6 border-l-2 border-slate-100 pb-2 last:pb-0 group/history">
                                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-white shadow-sm transition-colors duration-300
                                                {{ $application->status === 'pending' ? 'bg-amber-400 ring-4 ring-amber-50' : 'bg-slate-200' }}
                                                {{ $application->status === 'approved' ? 'bg-green-400 ring-4 ring-green-50' : '' }}
                                                {{ $application->status === 'rejected' ? 'bg-red-400 ring-4 ring-red-50' : '' }}
                                            "></div>
                                            
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                                    {{ $application->created_at->format('d M, Y') }}
                                                </span>
                                                <span class="px-2.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-lg
                                                    {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                                                    {{ $application->status === 'approved' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                                                    {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
                                                    {{ $application->status === 'cancelled' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                                    {{ $application->status }}
                                                </span>
                                            </div>

                                            @if ($application->preferred_hostel)
                                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 group-hover/history:bg-white group-hover/history:shadow-sm transition-all duration-300">
                                                    <p class="text-xs font-bold text-slate-700 flex items-center">
                                                        <svg class="w-3 h-3 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                        {{ $application->preferred_hostel }}
                                                    </p>
                                                    @if ($application->admin_notes)
                                                        <p class="text-[10px] text-slate-500 mt-2 italic border-t border-slate-100 pt-2">
                                                            "{{ $application->admin_notes }}"
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Inbox Empty</p>
                                    <p class="text-[10px] text-slate-300 mt-1">No past applications found.</p>
                                </div>
                            @endif
                        </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>