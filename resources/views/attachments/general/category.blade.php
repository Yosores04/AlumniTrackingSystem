@extends('layouts.tenant-app')

@section('title', ucfirst(str_replace('_', ' ', $category)) . ' Attachments')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('attachments.public') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                        <i class="fas fa-globe mr-2"></i>
                                        Public Attachments
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                        <h2 class="mt-2 text-2xl font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $category)) }} Attachments</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Browse {{ str_replace('_', ' ', $category) }} files shared by the community
                        </p>
                    </div>
                    @auth
                        <div class="flex space-x-3">
                            <a href="{{ route('attachments.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-folder mr-2"></i>
                                My Attachments
                            </a>
                            <a href="{{ route('attachments.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-upload mr-2"></i>
                                Upload File
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Category Navigation -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Other Categories</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @php
                        $categories = [
                            'document' => ['label' => 'Documents', 'icon' => 'fas fa-file-alt'],
                            'certificate' => ['label' => 'Certificates', 'icon' => 'fas fa-certificate'],
                            'transcript' => ['label' => 'Transcripts', 'icon' => 'fas fa-scroll'],
                            'photo' => ['label' => 'Photos', 'icon' => 'fas fa-image'],
                            'presentation' => ['label' => 'Presentations', 'icon' => 'fas fa-presentation'],
                            'research' => ['label' => 'Research', 'icon' => 'fas fa-search'],
                            'portfolio' => ['label' => 'Portfolio', 'icon' => 'fas fa-briefcase'],
                            'assignment' => ['label' => 'Assignments', 'icon' => 'fas fa-tasks'],
                            'lesson_plan' => ['label' => 'Lesson Plans', 'icon' => 'fas fa-chalkboard-teacher'],
                            'curriculum' => ['label' => 'Curriculum', 'icon' => 'fas fa-book'],
                        ];
                    @endphp
                    
                    @foreach($categories as $key => $categoryInfo)
                        <a href="{{ route('attachments.category', $key) }}" 
                           class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200 {{ $key === $category ? 'border-blue-500 bg-blue-50' : '' }}">
                            <i class="{{ $categoryInfo['icon'] }} {{ $key === $category ? 'text-blue-600' : 'text-blue-500' }} text-2xl mb-2"></i>
                            <span class="text-sm font-medium {{ $key === $category ? 'text-blue-900' : 'text-gray-900' }}">{{ $categoryInfo['label'] }}</span>
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
                                                @if($attachment->uploaded_by === auth()->id())
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-user mr-1"></i>
                                                        Your File
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-gray-500">
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
                                            @if($attachment->uploaded_by === auth()->id())
                                                <!-- Edit Own File -->
                                                <a href="{{ route('attachments.edit', $attachment) }}" 
                                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit
                                                </a>
                                            @endif
                                            
                                            @if(auth()->user()->isInstructor() || auth()->user()->isAdmin())
                                                <!-- Verification Toggle -->
                                                <form method="POST" action="{{ route('attachments.verify', $attachment) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                           class="inline-flex items-center px-3 py-2 border border-{{ $attachment->is_verified ? 'yellow' : 'green' }}-300 shadow-sm text-sm leading-4 font-medium rounded-md text-{{ $attachment->is_verified ? 'yellow' : 'green' }}-700 bg-white hover:bg-{{ $attachment->is_verified ? 'yellow' : 'green' }}-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $attachment->is_verified ? 'yellow' : 'green' }}-500">
                                                        @if($attachment->is_verified)
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                            Unverify
                                                        @else
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Verify
                                                        @endif
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No {{ str_replace('_', ' ', $category) }} files yet</h3>
                    <p class="text-gray-500 mb-6">No {{ str_replace('_', ' ', $category) }} files have been shared yet.</p>
                    @auth
                        <a href="{{ route('attachments.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-upload mr-2"></i>
                            Share Your First {{ ucfirst(str_replace('_', ' ', $category)) }} File
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>
@endsection