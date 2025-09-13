@extends(auth()->user()->role === 'instructor' ? 'layouts.instructor' : 'layouts.app')

@section('title', 'Instructor Note Details - ' . $alumni->name)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Instructor Note Details</h2>
                        <p class="text-gray-600">{{ $alumni->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        @can('update', $instructorNote)
                            <a href="{{ route('alumni.instructor-notes.edit', [$alumni, $instructorNote]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <i class="fas fa-edit mr-2"></i> Edit Note
                            </a>
                        @endcan
                        <a href="{{ route('alumni.instructor-notes.index', $alumni) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Notes
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <!-- Note Header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-xl font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $instructorNote->note_type) }} Note</h3>
                                
                                <!-- Priority Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $instructorNote->priority == 'high' ? 'bg-red-100 text-red-800' : 
                                       ($instructorNote->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($instructorNote->priority) }} Priority
                                </span>
                                
                                <!-- Private Badge -->
                                @if($instructorNote->is_private)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-lock mr-1"></i> Private Note
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-eye mr-1"></i> Public Note
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Note Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Instructor</h4>
                            <p class="text-gray-900 font-medium">{{ $instructorNote->instructor->name }}</p>
                            <p class="text-sm text-gray-600">{{ ucfirst($instructorNote->instructor->role) }}</p>
                        </div>

                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Note Date</h4>
                            <p class="text-gray-900">{{ $instructorNote->note_date ? $instructorNote->note_date->format('F j, Y') : 'Not specified' }}</p>
                        </div>

                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Created</h4>
                            <p class="text-gray-900">{{ $instructorNote->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>

                        @if($instructorNote->updated_at != $instructorNote->created_at)
                            <div class="bg-white rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Last Updated</h4>
                                <p class="text-gray-900">{{ $instructorNote->updated_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Note Content -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">Note Content</h4>
                        <div class="bg-white rounded-lg p-6 border {{ $instructorNote->priority == 'high' ? 'border-red-200' : ($instructorNote->priority == 'medium' ? 'border-yellow-200' : 'border-gray-200') }}">
                            <p class="text-gray-700 whitespace-pre-line text-base leading-relaxed">{{ $instructorNote->note }}</p>
                        </div>
                    </div>

                    @can('update', $instructorNote)
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <form action="{{ route('alumni.instructor-notes.destroy', [$alumni, $instructorNote]) }}" 
                                  method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        onclick="return confirm('Are you sure you want to delete this note? This action cannot be undone.')">
                                    <i class="fas fa-trash mr-2"></i> Delete Note
                                </button>
                            </form>
                            <a href="{{ route('alumni.instructor-notes.edit', [$alumni, $instructorNote]) }}" 
                               class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-2"></i> Edit Note
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection