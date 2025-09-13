@extends(auth()->user()->role === 'instructor' ? 'layouts.instructor' : 'layouts.app')

@section('title', 'Employment Details - ' . $alumni->name)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Employment Details</h2>
                        <p class="text-gray-600">{{ $alumni->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        @canany(['update'], $alumni)
                            <a href="{{ route('alumni.employment-history.edit', [$alumni, $employmentHistory]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                        @endcanany
                        <a href="{{ route('alumni.employment-history.index', $alumni) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $employmentHistory->position }}</h3>
                                <p class="text-lg text-blue-600 font-medium">{{ $employmentHistory->company_name }}</p>
                                @if($employmentHistory->location)
                                    <p class="text-gray-600 mt-1">
                                        <i class="fas fa-map-marker-alt mr-2"></i>{{ $employmentHistory->location }}
                                    </p>
                                @endif
                            </div>
                            @if($employmentHistory->is_current)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-circle mr-2 text-xs"></i>Current Position
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Employment Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Employment Type</h4>
                            <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', $employmentHistory->employment_type) }}</p>
                        </div>

                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Duration</h4>
                            <p class="text-gray-900">
                                {{ $employmentHistory->start_date->format('F Y') }} - 
                                {{ $employmentHistory->end_date ? $employmentHistory->end_date->format('F Y') : 'Present' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">{{ $employmentHistory->duration }} months</p>
                        </div>

                        @if($employmentHistory->salary)
                            <div class="bg-white rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Salary</h4>
                                <p class="text-gray-900">{{ $employmentHistory->currency }} {{ number_format($employmentHistory->salary, 2) }}</p>
                            </div>
                        @endif

                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Added</h4>
                            <p class="text-gray-900">{{ $employmentHistory->created_at->format('F j, Y') }}</p>
                            @if($employmentHistory->updated_at != $employmentHistory->created_at)
                                <p class="text-sm text-gray-600 mt-1">Updated: {{ $employmentHistory->updated_at->format('F j, Y') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($employmentHistory->description)
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Job Description</h4>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-line">{{ $employmentHistory->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if($employmentHistory->achievements)
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Key Achievements</h4>
                            <div class="bg-white rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-line">{{ $employmentHistory->achievements }}</p>
                            </div>
                        </div>
                    @endif

                    @if($employmentHistory->skills && count($employmentHistory->skills) > 0)
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-3">Skills Used</h4>
                            <div class="bg-white rounded-lg p-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($employmentHistory->skills as $skill)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @canany(['update'], $alumni)
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <form action="{{ route('alumni.employment-history.destroy', [$alumni, $employmentHistory]) }}" 
                                  method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        onclick="return confirm('Are you sure you want to delete this employment record? This action cannot be undone.')">
                                    <i class="fas fa-trash mr-2"></i> Delete Employment Record
                                </button>
                            </form>
                            <a href="{{ route('alumni.employment-history.edit', [$alumni, $employmentHistory]) }}" 
                               class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-2"></i> Edit Employment Record
                            </a>
                        </div>
                    @endcanany
                </div>
            </div>
        </div>
    </div>
</div>
@endsection