<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!
                </h1>
                <p class="mt-2 text-slate-700">Here's what's happening with your hostel accommodation.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Profile Card -->
                <div>
                    <!-- Profile Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="flex items-center space-x-4 mb-6">

                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $student->full_name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $student->student_id }}</p>
                                </div>
                            </div>

                            <div class="space-y-3 border-t border-gray-100 pt-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Department</span>
                                    <span class="font-medium text-gray-900">{{ $student->department }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Level</span>
                                    <span class="font-medium text-gray-900">{{ ucfirst($student->study_level) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Year</span>
                                    <span class="font-medium text-gray-900">{{ $student->year }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Gender</span>
                                    <span class="font-medium text-gray-900">{{ ucfirst($student->gender) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Allocation Details -->
                <div class="space-y-6">
                    @if($allocation)
                        <!-- Allocation Status Card -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Current Allocation</h3>
                                        <p class="text-sm text-gray-500">Academic Year {{ $allocation->academic_year }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">
                                        {{ ucfirst($allocation->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hostel</span>
                                        <div class="mt-1 text-xl font-bold text-gray-900">{{ $allocation->hostel->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $allocation->hostel->gender }} Hostel</div>
                                    </div>

                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Room</span>
                                        <div class="mt-1 text-xl font-bold text-gray-900">
                                            {{ $allocation->room->room_number }}
                                        </div>
                                        <div class="text-sm text-gray-500">Floor {{ $allocation->room->floor }}</div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-xs text-gray-500 block">Allocated On</span>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $allocation->allocation_date->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 block">Room Capacity</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $allocation->room->capacity }}
                                            Students</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Roommates Card -->
                        @if($roommates->isNotEmpty())
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Roommates</h3>
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($roommates as $roommate)
                                            <div
                                                class="flex items-start p-3 border border-gray-200 rounded-lg hover:border-gray-300 transition-colors">

                                                <div class="min-w-0 flex-1">
                                                    <div class="font-medium text-gray-900 truncate">{{ $roommate->full_name }}</div>
                                                    <div class="text-xs text-gray-500 truncate">{{ $roommate->department }}</div>
                                                    @if($roommate->phone)
                                                        <div class="text-xs text-gray-500 flex items-center mt-1">
                                                            <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            {{ $roommate->phone }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- No Allocation State -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-amber-500">
                            <div class="p-8 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 mb-4">
                                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">No Room Allocated Yet</h3>
                                <p class="text-gray-500 max-w-md mx-auto">
                                    You haven't been allocated a room for the current academic year.
                                    Please wait for the automated allocation process or contact the hostel administration.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions - Full Width at Bottom -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($allocation)
                            @if($allocation->receipt)
                                <a href="{{ route('student.receipt.download', $allocation->id) }}"
                                    class="flex items-center justify-center px-4 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download Receipt
                                </a>
                                <a href="{{ route('student.receipt.download', $allocation->id) }}" target="_blank"
                                    onclick="setTimeout(() => window.print(), 500); return true;"
                                    class="flex items-center justify-center px-4 py-3 bg-slate-900 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print Receipt
                                </a>
                            @else
                                <form method="POST" action="{{ route('student.receipt.generate', $allocation->id) }}"
                                    class="w-full md:col-span-2">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-4 py-3 bg-slate-900 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-slate-800 focus:bg-slate-800 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Generate Receipt
                                    </button>
                                </form>
                            @endif

                            @if($pendingRequest)
                                <button disabled
                                    class="flex items-center justify-center px-4 py-3 bg-gray-100 border border-transparent rounded-md font-semibold text-sm text-gray-400 uppercase tracking-widest cursor-not-allowed @if(!$allocation->receipt) md:col-span-3 @endif">
                                    Request Pending ({{ $pendingRequest->created_at->format('d M Y') }})
                                </button>
                            @else
                                <a href="{{ route('student.room-change') }}"
                                    class="flex items-center justify-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 @if(!$allocation->receipt) md:col-span-3 @endif">
                                    Request Room Change
                                </a>
                            @endif
                        @else
                            <button disabled
                                class="flex items-center justify-center px-4 py-3 bg-gray-100 border border-transparent rounded-md font-semibold text-sm text-gray-400 uppercase tracking-widest cursor-not-allowed md:col-span-3">
                                Actions Available After Allocation
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>