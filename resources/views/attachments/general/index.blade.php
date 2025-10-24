@extends('layouts.tenant-app')

@section('title', 'My Attachments')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">My Attachments</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Manage your uploaded documents and files
                        </p>
                    </div>
                    <a href="{{ route('attachments.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-upload mr-2"></i>
                        Upload File
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Files</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $attachments->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Verified</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $attachments->where('is_verified', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-globe text-purple-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Public</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $attachments->where('is_public', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-hdd text-orange-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Storage Used</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ number_format($attachments->sum('file_size') / 1024 / 1024, 1) }} MB
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attachments List -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
            @if($attachments->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($attachments as $attachment)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i class="{{ $attachment->file_type_icon }} text-2xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <p class="text-sm font-medium text-blue-600 truncate">
                                                    {{ $attachment->original_name }}
                                                </p>
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
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-gray-500">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $attachment->category)) }}
                                                    </p>
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                        <i class="fas fa-file-alt mr-1"></i>
                                                        {{ $attachment->human_file_size }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    <p>
                                                        Uploaded {{ $attachment->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if($attachment->description)
                                                <p class="mt-2 text-sm text-gray-600">{{ $attachment->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- View/Download -->
                                        <a href="{{ route('attachments.download', $attachment) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-download mr-1"></i>
                                            Download
                                        </a>
                                        
                                        <!-- Edit -->
                                        <a href="{{ route('attachments.edit', $attachment) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('attachments.destroy', $attachment) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                   class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                   onclick="return confirm('Are you sure you want to delete this file?')">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $attachments->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-upload text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No attachments yet</h3>
                    <p class="text-gray-500 mb-6">Upload your first document to get started.</p>
                    <a href="{{ route('attachments.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Your First File
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection