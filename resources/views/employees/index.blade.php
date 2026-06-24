<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div style="color: green; margin-bottom: 15px; font-weight: bold;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <a href="{{ route('employees.create') }}">
                        <button style="background-color: #000; color: #fff; padding: 8px 15px; border-radius: 5px; cursor: pointer; margin-bottom: 20px;">
                            + Add Employee
                        </button>
                    </a>

                    <div style="overflow-x: auto; max-width: 100%; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <table style="width: 100%; text-align: left; border-collapse: collapse; white-space: nowrap;">
                            <thead>
                                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Employee ID</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Name</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Position</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Department</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Branch</th>
                                    <th style="padding: 12px 14px; font-size: 0.85rem; font-weight: 700;">Contact No</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $index => $employee)
                                    @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#f9fafb'; @endphp
                                    <tr style="border-bottom: 1px solid #e5e7eb; background-color: {{ $rowBg }};">
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->nfp_id }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem; font-weight: bold;">{{ $employee->full_name }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->position }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->department ?? '—' }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->branch }}</td>
                                        <td style="padding: 10px 14px; font-size: 0.85rem;">{{ $employee->contact_no ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">No employees recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
