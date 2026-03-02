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

                    {{-- Student Info Card --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Your Profile</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Name</span>
                                <span class="font-medium text-gray-900">{{ $student->full_name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Student ID</span>
                                <span class="font-medium text-gray-900">{{ $student->student_id }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Gender</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($student->gender) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Department</span>
                                <span class="font-medium text-gray-900 text-right">{{ $student->department }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Level</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($student->study_level) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Application History --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Application History
                            </h3>
                        </div>
                        <div class="p-5">
                            @if ($applications->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach ($applications as $application)
                                        <div class="border border-gray-100 rounded-lg p-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs text-gray-500">
                                                    {{ $application->created_at->format('d M Y') }}
                                                </span>
                                                <span
                                                    class="px-2 py-0.5 text-xs font-semibold rounded-full
                                                            {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                                            {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                                            {{ $application->status === 'cancelled' ? 'bg-gray-100 text-gray-600' : '' }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </div>
                                            @if ($application->preferred_hostel)
                                                <p class="text-xs text-gray-600">
                                                    Preferred: {{ $application->preferred_hostel }}</p>
                                            @endif
                                            @if ($application->admin_notes)
                                                <p class="text-xs text-gray-500 mt-1 italic">
                                                    "{{ $application->admin_notes }}"</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-400">No applications yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>