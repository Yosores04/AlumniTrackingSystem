@extends(auth()->user()->role === 'instructor' ? 'layouts.instructor' : 'layouts.app')

@section('title', 'Instructor Notes - ' . $alumni->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Instructor Notes</h2>
                        <p class="text-gray-600">{{ $alumni->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('alumni.show', $alumni) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Profile
                        </a>
                        @can('create', App\Models\InstructorNote::class)
                            <a href="{{ route('alumni.instructor-notes.create', $alumni) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add Note
                            </a>
                        @endcan
                    </div>
                </div>
                
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if($instructorNotes->count() > 0)
                    <div class="space-y-4">
                        @foreach($instructorNotes as $note)
                            <div class="border rounded-lg p-6 {{ $note->priority == 'high' ? 'border-red-300 bg-red-50' : ($note->priority == 'medium' ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200') }}">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $note->note_type) }} Note</h3>
                                            
                                            <!-- Priority Badge -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $note->priority == 'high' ? 'bg-red-100 text-red-800' : 
                                                   ($note->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($note->priority) }} Priority
                                            </span>
                                            
                                            <!-- Private Badge -->
                                            @if($note->is_private)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-lock mr-1"></i> Private
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="text-sm text-gray-600 mb-3">
                                            <span class="font-medium">By: {{ $note->instructor->name }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $note->note_date ? $note->note_date->format('M j, Y') : $note->created_at->format('M j, Y') }}</span>
                                            @if($note->created_at != $note->updated_at)
                                                <span class="mx-2">•</span>
                                                <span class="text-gray-500">Updated: {{ $note->updated_at->format('M j, Y g:i A') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-gray-700 whitespace-pre-line">{{ $note->note }}</p>
                                </div>

                                @can('update', $note)
                                    <div class="flex space-x-2 pt-4 border-t border-gray-200">
                                        <a href="{{ route('alumni.instructor-notes.show', [$alumni, $note]) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i> View Details
                                        </a>
                                        <a href="{{ route('alumni.instructor-notes.edit', [$alumni, $note]) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('alumni.instructor-notes.destroy', [$alumni, $note]) }}" 
                                              method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                    onclick="return confirm('Are you sure you want to delete this note?')">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                @endcan
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $instructorNotes->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400">
                            <i class="fas fa-sticky-note text-6xl mb-4"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Instructor Notes</h3>
                        <p class="text-gray-600 mb-6">No notes have been added for this alumni yet.</p>
                        @can('create', App\Models\InstructorNote::class)
                            <a href="{{ route('alumni.instructor-notes.create', $alumni) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i> Add First Note
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection