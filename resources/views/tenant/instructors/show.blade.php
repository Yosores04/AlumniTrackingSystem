<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white">
                {{ __('Instructor Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('tenant.instructors.edit', $instructor->id) }}" class="px-4 py-2 bg-accent text-white rounded-md hover:bg-accent-dark">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('tenant.instructors.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:space-x-6">
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                                <div class="mt-4 flex flex-col space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Name</span>
                                        <p class="mt-1 text-base text-gray-900">{{ $instructor->name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email</span>
                                        <p class="mt-1 text-base text-gray-900">{{ $instructor->email }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Role</span>
                                        <p class="mt-1 text-base text-gray-900">
                                            <span class="px-2 py-1 rounded-full bg-accent-10 text-accent text-xs font-medium">
                                                {{ $instructor->role }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:w-1/2">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                                <div class="mt-4 flex flex-col space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Created On</span>
                                        <p class="mt-1 text-base text-gray-900">{{ $instructor->created_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Last Updated</span>
                                        <p class="mt-1 text-base text-gray-900">{{ $instructor->updated_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email Verified</span>
                                        <p class="mt-1 text-base text-gray-900">
                                            @if ($instructor->email_verified_at)
                                                <span class="text-green-600">
                                                    <i class="fas fa-check-circle mr-1"></i> 
                                                    Verified on {{ $instructor->email_verified_at->format('F d, Y') }}
                                                </span>
                                            @else
                                                <span class="text-red-600">
                                                    <i class="fas fa-times-circle mr-1"></i> 
                                                    Not verified
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-6">
                    
                    <div class="flex items-center gap-4 mt-4">
                        <!-- Edit Button -->
                        <a href="{{ route('tenant.instructors.edit', $instructor->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>

                        <!-- Delete Button -->
                        <form method="POST" action="{{ route('tenant.instructors.destroy', $instructor->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this instructor?')">
                                <i class="fas fa-trash mr-1"></i> Delete Instructor
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 