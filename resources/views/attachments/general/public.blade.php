@extends('layouts.tenant-app')

@section('title', 'Public Attachments')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Public Attachments</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Browse verified public documents shared by the community
                        </p>
                    </div>
                    @auth
                        <a href="{{ route('attachments.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-folder mr-2"></i>
                            My Attachments
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Browse by Category</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @php
                        $categories = [
                            'document' => ['label' => 'Documents', 'icon' => 'fas fa-file-alt'],
                            'certificate' => ['label' => 'Certificates', 'icon' => 'fas fa-certificate'],
                            'transcript' => ['label' => 'Transcripts', 'icon' => 'fas fa-scroll'],
                            'photo' => ['label' => 'Photos', 'icon' => 'fas fa-image'],
                            'presentation' => ['label' => 'Presentations', 'icon' => 'fas fa-presentation'],
                            'research' => ['label' => 'Research', 'icon' => 'fas fa-search'],
                        ];
                    @endphp
                    
                    @foreach($categories as $key => $category)
                        <a href="{{ route('attachments.category', $key) }}" 
                           class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200">
                            <i class="{{ $category['icon'] }} text-blue-500 text-2xl mb-2"></i>
                            <span class="text-sm font-medium text-gray-900">{{ $category['label'] }}</span>
                        </a>
                    @endforeach
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
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Verified
                                                </span>
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-globe mr-1"></i>
                                                    Public
                                                </span>
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
                                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                        <i class="fas fa-user mr-1"></i>
                                                        {{ $attachment->uploader->name }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    <p>
                                                        Shared {{ $attachment->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                            @if($attachment->description)
                                                <p class="mt-2 text-sm text-gray-600">{{ $attachment->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Download -->
                                        <a href="{{ route('attachments.download', $attachment) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-download mr-1"></i>
                                            Download
                                        </a>
                                        
                                        @auth
                                            @if(auth()->user()->isInstructor() || auth()->user()->isAdmin())
                                                <!-- Verification Toggle -->
                                                <form method="POST" action="{{ route('attachments.verify', $attachment) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                           class="inline-flex items-center px-3 py-2 border border-yellow-300 shadow-sm text-sm leading-4 font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        Unverify
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No public attachments yet</h3>
                    <p class="text-gray-500 mb-6">No verified public documents have been shared yet.</p>
                    @auth
                        <a href="{{ route('attachments.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-upload mr-2"></i>
                            Share Your First File
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>
@endsection