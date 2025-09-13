@extends(auth()->user()->role === 'instructor' ? 'layouts.instructor' : 'layouts.app')

@section('title', 'Edit Instructor Note - ' . $alumni->name)

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Edit Instructor Note</h2>
                        <p class="text-gray-600">{{ $alumni->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('alumni.instructor-notes.show', [$alumni, $instructorNote]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            <i class="fas fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('alumni.instructor-notes.index', $alumni) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Notes
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500">
                        <div class="text-red-700">
                            <h4 class="font-medium mb-2">Please correct the following errors:</h4>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('alumni.instructor-notes.update', [$alumni, $instructorNote]) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Note Type -->
                        <div>
                            <label for="note_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Note Type <span class="text-red-500">*</span>
                            </label>
                            <select id="note_type" 
                                    name="note_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Select Type</option>
                                <option value="academic" {{ old('note_type', $instructorNote->note_type) == 'academic' ? 'selected' : '' }}>Academic</option>
                                <option value="behavioral" {{ old('note_type', $instructorNote->note_type) == 'behavioral' ? 'selected' : '' }}>Behavioral</option>
                                <option value="performance" {{ old('note_type', $instructorNote->note_type) == 'performance' ? 'selected' : '' }}>Performance</option>
                                <option value="general" {{ old('note_type', $instructorNote->note_type) == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                Priority <span class="text-red-500">*</span>
                            </label>
                            <select id="priority" 
                                    name="priority" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="low" {{ old('priority', $instructorNote->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $instructorNote->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $instructorNote->priority) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <!-- Note Date -->
                        <div>
                            <label for="note_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Note Date
                            </label>
                            <input type="date" 
                                   id="note_date" 
                                   name="note_date" 
                                   value="{{ old('note_date', $instructorNote->note_date ? $instructorNote->note_date->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Note Content -->
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                            Note Content <span class="text-red-500">*</span>
                        </label>
                        <textarea id="note" 
                                  name="note" 
                                  rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter your note about the student..."
                                  required>{{ old('note', $instructorNote->note) }}</textarea>
                    </div>

                    <!-- Privacy Setting -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_private" 
                                   name="is_private" 
                                   value="1"
                                   {{ old('is_private', $instructorNote->is_private) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <div class="ml-3">
                                <label for="is_private" class="block text-sm font-medium text-gray-900">
                                    Private Note
                                </label>
                                <p class="text-sm text-gray-600">
                                    Private notes are only visible to instructors and administrators. 
                                    Public notes can be seen by the alumni student.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Note Metadata -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Note Information</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Created by:</strong> {{ $instructorNote->instructor->name }}</p>
                            <p><strong>Created on:</strong> {{ $instructorNote->created_at->format('F j, Y \a\t g:i A') }}</p>
                            @if($instructorNote->updated_at != $instructorNote->created_at)
                                <p><strong>Last updated:</strong> {{ $instructorNote->updated_at->format('F j, Y \a\t g:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('alumni.instructor-notes.show', [$alumni, $instructorNote]) }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Update Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update background color based on priority selection
    const prioritySelect = document.getElementById('priority');
    const form = document.querySelector('form');
    
    function updatePriorityStyles() {
        const priority = prioritySelect.value;
        form.classList.remove('border-red-200', 'border-yellow-200', 'border-green-200');
        
        if (priority === 'high') {
            form.classList.add('border-red-200');
        } else if (priority === 'medium') {
            form.classList.add('border-yellow-200');
        } else {
            form.classList.add('border-green-200');
        }
    }
    
    prioritySelect.addEventListener('change', updatePriorityStyles);
    updatePriorityStyles(); // Initialize on page load
});
</script>
@endsection