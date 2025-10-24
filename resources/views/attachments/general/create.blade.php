@extends('layouts.tenant-app')

@section('title', 'Upload File')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <a href="{{ route('attachments.index') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-4">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Attachments
                    </a>
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Upload File</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Upload a new document or file (Max size: 10MB)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('attachments.store') }}" enctype="multipart/form-data" class="divide-y divide-gray-200">
                @csrf

                <!-- File Upload Section -->
                <div class="px-6 py-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-file-upload mr-2"></i>
                        Select File
                    </h3>

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200"
                         id="file-drop-zone">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-4"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="file" name="file" type="file" class="sr-only" required
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.zip,.rar,.txt"
                                           onchange="handleFileSelect(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, XLS, PPT, JPG, PNG, ZIP up to 10MB
                            </p>
                        </div>
                    </div>

                    <!-- File Info Display -->
                    <div id="file-info" class="mt-4 hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex items-center">
                                <i class="fas fa-file text-blue-500 text-2xl mr-3"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-blue-900" id="file-name"></p>
                                    <p class="text-sm text-blue-700" id="file-size"></p>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @error('file')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Details Section -->
                <div class="px-6 py-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        File Details
                    </h3>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category" name="category" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select a category</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="3" 
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Optional description of the file...">{{ old('description') }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">Brief description of the file content (optional, max 1000 characters)</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Visibility -->
                        <div>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_public" name="is_public" type="checkbox" value="1" 
                                           {{ old('is_public') ? 'checked' : '' }}
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_public" class="font-medium text-gray-700">Make this file public</label>
                                    <p class="text-gray-500">Allow other users to view and download this file</p>
                                </div>
                            </div>
                            @error('is_public')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="px-6 py-4 bg-gray-50 text-right">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('attachments.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-upload mr-2"></i>
                            Upload File
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleFileSelect(input) {
    const file = input.files[0];
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
    } else {
        fileInfo.classList.add('hidden');
    }
}

function clearFile() {
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('file-info');
    
    fileInput.value = '';
    fileInfo.classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Drag and drop functionality
const dropZone = document.getElementById('file-drop-zone');
const fileInput = document.getElementById('file');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-blue-400', 'bg-blue-50');
}

function unhighlight(e) {
    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        fileInput.files = files;
        handleFileSelect(fileInput);
    }
}
</script>
@endpush
@endsection