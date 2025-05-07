@extends('layouts.app')

@section('title', 'Alumni Management')

@section('content')
<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Alumni Management</h1>
        <div class="flex space-x-2">
            <a href="{{ route('alumni.report-form') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-file-pdf mr-2"></i> Generate Report
            </a>
            <a href="{{ route('alumni.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Add New
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form action="{{ route('alumni.index') }}" method="GET" class="mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="w-full sm:w-auto flex-1 min-w-0 max-w-xs">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <input type="text" id="search" name="search" class="block w-full pr-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search name or email..." value="{{ request('search') }}">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div class="w-full sm:w-auto">
                <label for="batch_year" class="block text-sm font-medium text-gray-700 mb-1">Batch Year</label>
                <select id="batch_year" name="batch_year" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">All Batch Years</option>
                    @foreach($batchYears as $year)
                        <option value="{{ $year }}" {{ request('batch_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label for="employment_status" class="block text-sm font-medium text-gray-700 mb-1">Employment Status</label>
                <select id="employment_status" name="employment_status" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    <option value="employed" {{ request('employment_status') == 'employed' ? 'selected' : '' }}>Employed</option>
                    <option value="unemployed" {{ request('employment_status') == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                    <option value="self_employed" {{ request('employment_status') == 'self_employed' ? 'selected' : '' }}>Self-employed</option>
                    <option value="student" {{ request('employment_status') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="other" {{ request('employment_status') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="w-full sm:w-auto sm:flex-none pt-5">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-900">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </div>
    </form>
    
    <!-- Alumni Table -->
    <div class="shadow border-b border-gray-200 sm:rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                        Alumni
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        Batch
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                        Employment
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        Verified
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($alumni as $alumnus)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($alumnus->profile_photo_path)
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($alumnus->profile_photo_path) }}" alt="{{ $alumnus->name }}">
                                </div>
                            @else
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">{{ substr($alumnus->first_name ?? '', 0, 1) . substr($alumnus->last_name ?? '', 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $alumnus->name }}</div>
                                <div class="text-sm text-gray-500">{{ $alumnus->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $alumnus->batch_year ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $alumnus->department ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($alumnus->employment_status)
                            @case('employed')
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-4 w-4 text-green-500">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div class="ml-2 text-sm font-medium text-gray-900">Employed</div>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">{{ $alumnus->current_employer ?? 'N/A' }}</div>
                                @break
                            @case('unemployed')
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-4 w-4 text-red-500">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="ml-2 text-sm font-medium text-gray-900">Unemployed</div>
                                </div>
                                @break
                            @case('self_employed')
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-4 w-4 text-blue-500">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="ml-2 text-sm font-medium text-gray-900">Self-employed</div>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">{{ $alumnus->current_employer ?? 'N/A' }}</div>
                                @break
                            @case('student')
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-4 w-4 text-indigo-500">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="ml-2 text-sm font-medium text-gray-900">Student</div>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">{{ $alumnus->current_employer ?? 'N/A' }}</div>
                                @break
                            @case('other')
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-4 w-4 text-gray-500">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="ml-2 text-sm font-medium text-gray-900">Other</div>
                                </div>
                                <div class="text-sm text-gray-500 mt-1">{{ $alumnus->current_employer ?? 'N/A' }}</div>
                                @break
                            @default
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-4 w-4 text-gray-400">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <div class="ml-2 text-sm font-medium text-gray-900">N/A</div>
                                </div>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($alumnus->is_verified)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Unverified
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end items-center space-x-2">
                            <a href="{{ route('alumni.show', $alumnus->id) }}" class="text-gray-500 hover:text-gray-700 p-1" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('alumni.edit', $alumnus->id) }}" class="text-gray-500 hover:text-gray-700 p-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="delete-form-{{ $alumnus->id }}" action="{{ route('alumni.destroy', $alumnus->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-500 hover:text-red-700 p-1" title="Delete" 
                                        onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                title: 'Delete Alumni Record',
                                                message: 'Are you sure you want to delete this alumni record? This action cannot be undone.',
                                                type: 'danger',
                                                confirmButtonText: 'Delete',
                                                onConfirm: () => document.getElementById('delete-form-{{ $alumnus->id }}').submit()
                                            }
                                        }))">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <i class="fas fa-search mb-3 text-3xl"></i>
                            <span class="text-lg font-medium">No alumni records found</span>
                            <p class="mt-1 text-sm">Try adjusting your search or filter criteria</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $alumni->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any needed initialization scripts here
</script>
@endpush 