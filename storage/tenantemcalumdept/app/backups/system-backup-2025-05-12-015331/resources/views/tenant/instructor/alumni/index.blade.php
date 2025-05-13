@extends('layouts.instructor')

@section('title', 'Alumni Management')

@section('content')
<div class="content-card">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold section-heading">Alumni Management</h2>
        <div class="flex space-x-3">
            <a href="{{ route('instructor.alumni.report-form') }}" class="btn btn-secondary">
                <i class="fas fa-file-pdf mr-2"></i> Generate Report
            </a>
            <a href="{{ route('instructor.alumni.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Add New
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 p-5 rounded-lg mb-6 border border-gray-100">
        <form action="{{ route('instructor.alumni.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div>
                <label class="form-label">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" class="form-control pl-10" placeholder="Search name or email..." value="{{ $filters['search'] ?? '' }}">
                </div>
            </div>
            <div>
                <label class="form-label">Batch Year</label>
                <select name="batch_year" class="form-control">
                    <option value="">All Batch Years</option>
                    @foreach($batchYears as $year)
                        <option value="{{ $year }}" {{ isset($filters['batch_year']) && $filters['batch_year'] == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Employment Status</label>
                <select name="employment_status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="employed" {{ isset($filters['employment_status']) && $filters['employment_status'] == 'employed' ? 'selected' : '' }}>Employed</option>
                    <option value="unemployed" {{ isset($filters['employment_status']) && $filters['employment_status'] == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                    <option value="self_employed" {{ isset($filters['employment_status']) && $filters['employment_status'] == 'self_employed' ? 'selected' : '' }}>Self-employed</option>
                    <option value="student" {{ isset($filters['employment_status']) && $filters['employment_status'] == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="other" {{ isset($filters['employment_status']) && $filters['employment_status'] == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary py-2.5 px-4 w-full">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    <!-- Alumni Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-100">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Alumni</th>
                    <th>Batch</th>
                    <th>Employment</th>
                    <th>Verified</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumni as $alum)
                <tr>
                    <td>
                        <div class="flex items-center">
                            @if($alum->profile_photo_path)
                                <div class="avatar mr-3">
                                    <img src="{{ Storage::url($alum->profile_photo_path) }}" alt="{{ $alum->name }}">
                                </div>
                            @else
                                <div class="avatar bg-blue-100 text-blue-600 mr-3">
                                    <span class="font-medium">{{ substr($alum->first_name, 0, 1) . substr($alum->last_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $alum->name }}</div>
                                <div class="text-sm text-gray-500">{{ $alum->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="font-medium">{{ $alum->batch_year ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $alum->department ?? 'N/A' }}</div>
                    </td>
                    <td>
                        @switch($alum->employment_status)
                            @case('employed')
                                <span class="badge badge-success">
                                    <i class="fas fa-briefcase mr-1"></i> Employed
                                </span>
                                @break
                            @case('unemployed')
                                <span class="badge badge-danger">
                                    <i class="fas fa-search mr-1"></i> Unemployed
                                </span>
                                @break
                            @case('self_employed')
                                <span class="badge badge-info">
                                    <i class="fas fa-user-tie mr-1"></i> Self-employed
                                </span>
                                @break
                            @case('student')
                                <span class="badge badge-info">
                                    <i class="fas fa-graduation-cap mr-1"></i> Student
                                </span>
                                @break
                            @case('other')
                                <span class="badge badge-secondary">
                                    <i class="fas fa-user mr-1"></i> Other
                                </span>
                                @break
                            @default
                                <span class="badge badge-secondary">
                                    <i class="fas fa-question-circle mr-1"></i> Unknown
                                </span>
                        @endswitch
                        <div class="text-sm text-gray-500 mt-2">{{ $alum->current_employer ?? 'N/A' }}</div>
                    </td>
                    <td>
                        @if($alum->is_verified)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i> Verified
                            </span>
                        @else
                            <span class="badge badge-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Unverified
                            </span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('instructor.alumni.show', $alum->id) }}" class="btn btn-secondary py-1 px-2" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('instructor.alumni.edit', $alum->id) }}" class="btn btn-secondary py-1 px-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="delete-form-{{ $alum->id }}" action="{{ route('instructor.alumni.destroy', $alum->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="button" 
                                    class="btn btn-secondary py-1 px-2 text-red-600 hover:text-red-800" 
                                    title="Delete"
                                    onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                                        detail: {
                                            title: 'Delete Alumni Record',
                                            message: 'Are you sure you want to delete this alumni record? This action cannot be undone.',
                                            type: 'danger',
                                            confirmButtonText: 'Delete',
                                            onConfirm: () => document.getElementById('delete-form-{{ $alum->id }}').submit()
                                        }
                                    }))"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8">
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
        {{ $alumni->withQueryString()->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Any additional scripts needed can go here
</script>
@endpush 