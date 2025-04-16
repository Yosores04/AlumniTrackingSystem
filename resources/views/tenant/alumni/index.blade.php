<style>
    /* Alumni directory styles */
    .alumni-card {
        background-color: var(--content-bg);
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 0.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
    }
    
    .alumni-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(var(--brand-primary-rgb), 0.1), 0 4px 6px -2px rgba(var(--brand-primary-rgb), 0.05);
    }
    
    .alumni-filter {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
    }
    
    .filter-badge {
        background-color: var(--brand-primary-light);
        color: var(--brand-primary);
        border-radius: 9999px;
        padding: 0.25rem 0.75rem;
    }
    
    .filter-badge.active {
        background-color: var(--brand-primary);
        color: white;
    }
    
    .search-input {
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .search-input:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.15);
        outline: none;
    }
    
    .profile-image {
        background-color: var(--brand-secondary-light);
        color: var(--brand-secondary);
    }
    
    .alumni-badge {
        background-color: rgba(var(--accent-color-rgb), 0.15);
        color: var(--accent-color);
        font-size: 0.75rem;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
    }
    
    .section-title {
        color: var(--text-primary);
        border-bottom: 2px solid var(--brand-primary-light);
    }
</style>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Alumni Directory</h1>
            <a href="{{ route('tenant.alumni.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-md">Add New Alumni</a>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6">
            <div class="alumni-filter p-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="w-full md:w-1/3">
                        <form action="{{ route('tenant.alumni.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search alumni..." class="search-input w-full">
                                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('tenant.alumni.index') }}" class="filter-badge {{ !request('batch_year') && !request('course') ? 'active' : '' }}">All</a>
                        
                        @foreach($batchYears as $year)
                            <a href="{{ route('tenant.alumni.index', ['batch_year' => $year]) }}" class="filter-badge {{ request('batch_year') == $year ? 'active' : '' }}">{{ $year }}</a>
                        @endforeach
                        
                        <div class="relative group">
                            <button class="filter-badge flex items-center">
                                Course <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 z-10 hidden group-hover:block">
                                @foreach($courses as $course)
                                    <a href="{{ route('tenant.alumni.index', ['course' => $course]) }}" class="block px-4 py-2 hover:bg-gray-100 {{ request('course') == $course ? 'bg-primary-light' : '' }}">{{ $course }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alumni List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($alumni as $alumnus)
                <div class="alumni-card p-6">
                    <div class="flex items-start">
                        <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                            @if($alumnus->profile_image)
                                <img src="{{ Storage::url($alumnus->profile_image) }}" alt="{{ $alumnus->first_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="profile-image w-full h-full flex items-center justify-center text-xl font-semibold">
                                    {{ substr($alumnus->first_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <h3 class="font-semibold text-lg">{{ $alumnus->first_name }} {{ $alumnus->last_name }}</h3>
                                @if($alumnus->is_verified)
                                    <span class="alumni-badge">Verified</span>
                                @endif
                            </div>
                            
                            <p class="text-gray-600 text-sm">{{ $alumnus->course }} ({{ $alumnus->batch_year }})</p>
                            
                            @if($alumnus->current_position)
                                <div class="mt-2 flex items-center text-sm text-gray-600">
                                    <i class="fas fa-briefcase mr-2"></i>
                                    <span>{{ $alumnus->current_position }}</span>
                                </div>
                            @endif
                            
                            @if($alumnus->current_company)
                                <div class="mt-1 flex items-center text-sm text-gray-600">
                                    <i class="fas fa-building mr-2"></i>
                                    <span>{{ $alumnus->current_company }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex justify-between">
                            <a href="{{ route('tenant.alumni.show', $alumnus) }}" class="text-brand-primary hover:text-primary-hover transition">
                                <i class="fas fa-user mr-1"></i> View Profile
                            </a>
                            
                            @if(auth()->user()->can('message', $alumnus))
                                <a href="{{ route('tenant.messages.create', ['recipient' => $alumnus->id]) }}" class="text-brand-primary hover:text-primary-hover transition">
                                    <i class="fas fa-envelope mr-1"></i> Message
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No alumni records found matching your criteria.</p>
                    <a href="{{ route('tenant.alumni.create') }}" class="mt-4 inline-block px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-md">Add New Alumni</a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $alumni->appends(request()->query())->links() }}
        </div>
    </div>
</div> 