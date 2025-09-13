@extends(auth()->user()->role === 'instructor' ? 'layouts.instructor' : 'layouts.app')

@section('title', 'Employment History - ' . $alumni->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Employment History</h2>
                        <p class="text-gray-600">{{ $alumni->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('alumni.show', $alumni) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
                        </a>
                        @canany(['update'], $alumni)
                            <a href="{{ route('alumni.employment-history.create', $alumni) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add Employment
                            </a>
                        @endcanany
                    </div>
                </div>
                
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($employmentHistories->count() > 0)
                    <div class="space-y-6">
                        @foreach($employmentHistories as $employment)
                            <div class="border rounded-lg p-6 {{ $employment->is_current ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $employment->position }}</h3>
                                        <p class="text-blue-600 font-medium">{{ $employment->company_name }}</p>
                                        @if($employment->location)
                                            <p class="text-gray-600">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $employment->location }}
                                            </p>
                                        @endif
                                    </div>
                                    @if($employment->is_current)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Current Position
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Employment Type</p>
                                        <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', $employment->employment_type) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Duration</p>
                                        <p class="text-gray-900">
                                            {{ $employment->start_date->format('M Y') }} - 
                                            {{ $employment->end_date ? $employment->end_date->format('M Y') : 'Present' }}
                                            <span class="text-gray-500">({{ $employment->duration }} months)</span>
                                        </p>
                                    </div>
                                    @if($employment->salary)
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Salary</p>
                                            <p class="text-gray-900">{{ $employment->currency }} {{ number_format($employment->salary, 2) }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($employment->description)
                                    <div class="mb-4">
                                        <p class="text-sm font-medium text-gray-500 mb-2">Description</p>
                                        <p class="text-gray-700">{{ $employment->description }}</p>
                                    </div>
                                @endif

                                @if($employment->achievements)
                                    <div class="mb-4">
                                        <p class="text-sm font-medium text-gray-500 mb-2">Key Achievements</p>
                                        <p class="text-gray-700">{{ $employment->achievements }}</p>
                                    </div>
                                @endif

                                @if($employment->skills && count($employment->skills) > 0)
                                    <div class="mb-4">
                                        <p class="text-sm font-medium text-gray-500 mb-2">Skills</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($employment->skills as $skill)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @canany(['update'], $alumni)
                                    <div class="flex space-x-2 mt-4 pt-4 border-t border-gray-200">
                                        <a href="{{ route('alumni.employment-history.show', [$alumni, $employment]) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i> View Details
                                        </a>
                                        <a href="{{ route('alumni.employment-history.edit', [$alumni, $employment]) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('alumni.employment-history.destroy', [$alumni, $employment]) }}" 
                                              method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                    onclick="return confirm('Are you sure you want to delete this employment record?')">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                @endcanany
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $employmentHistories->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400">
                            <i class="fas fa-briefcase text-6xl mb-4"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Employment History</h3>
                        <p class="text-gray-600 mb-6">No employment records have been added yet.</p>
                        @canany(['update'], $alumni)
                            <a href="{{ route('alumni.employment-history.create', $alumni) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add First Employment Record
                            </a>
                        @endcanany
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection