<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Bulk Room Allocation</h2>
            <p class="text-gray-600 mb-6">
                This will automatically allocate rooms to all students who don't currently have room assignments.
                The system will match students to appropriate rooms based on gender and availability.
            </p>

            <div class="mb-6">
                <h3 class="font-semibold mb-2">Current Statistics:</h3>
                <ul class="list-disc list-inside text-gray-700">
                    <li>Total Students: {{ \App\Models\Student::count() }}</li>
                    <li>Allocated: {{ \App\Models\Student::has('allocation')->count() }}</li>
                    <li>Unallocated: {{ \App\Models\Student::unallocated()->count() }}</li>
                </ul>
            </div>

            <x-filament::button wire:click="allocateAll" color="primary" size="lg">
                Allocate All Unallocated Students
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>