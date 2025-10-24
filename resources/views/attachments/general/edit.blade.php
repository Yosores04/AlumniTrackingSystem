@extends('layouts.tenant-app')

@section('title', 'Edit Attachment')

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
                        <h2 class="text-2xl font-semibold text-gray-900">Edit Attachment</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Update attachment details for {{ $attachment->original_name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current File Info -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-file mr-2"></i>
                    Current File
                </h3>
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="{{ $attachment->file_type_icon }} text-3xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center">
                            <p class="text-lg font-medium text-gray-900">{{ $attachment->original_name }}</p>
                            @if($attachment->is_verified)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Verified
                                </span>
                            @endif
                            @if($attachment->is_public)
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-globe mr-1"></i>
                                    Public
                                </span>
                            @endif
                        </div>
                        <div class="mt-1 flex items-center text-sm text-gray-500">
                            <span class="mr-4">
                                <i class="fas fa-hdd mr-1"></i>
                                {{ $attachment->human_file_size }}
                            </span>
                            <span class="mr-4">
                                <i class="fas fa-calendar mr-1"></i>
                                Uploaded {{ $attachment->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('attachments.download', $attachment) }}" 
                               class="text-blue-600 hover:text-blue-500">
                                <i class="fas fa-download mr-1"></i>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form method="POST" action="{{ route('attachments.update', $attachment) }}">
                @csrf
                @method('PUT')

                <div class="px-6 py-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-edit mr-2"></i>
                        Update Details
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
                                    <option value="{{ $key }}" 
                                            {{ (old('category', $attachment->category) == $key) ? 'selected' : '' }}>
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
                                      placeholder="Optional description of the file...">{{ old('description', $attachment->description) }}</textarea>
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
                                           {{ old('is_public', $attachment->is_public) ? 'checked' : '' }}
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
                            <i class="fas fa-save mr-2"></i>
                            Update Attachment
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Additional Actions -->
        @if(auth()->user()->isInstructor() || auth()->user()->isAdmin())
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-cog mr-2"></i>
                    Administrative Actions
                </h3>
                <div class="flex space-x-3">
                    <!-- Toggle Verification -->
                    <form method="POST" action="{{ route('attachments.verify', $attachment) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ $attachment->is_verified ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            @if($attachment->is_verified)
                                <i class="fas fa-times-circle mr-2"></i>
                                Mark as Unverified
                            @else
                                <i class="fas fa-check-circle mr-2"></i>
                                Mark as Verified
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection