@extends('layouts.app')

@section('title', 'Alumni Management')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Instructor Details</h1>
                        <div class="flex space-x-2">
                            <a href="{{ route('tenant.instructors.edit', $instructor->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <a href="{{ route('tenant.instructors.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Instructors
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Name</label>
                                    <div class="text-base">{{ $instructor->name }}</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                    <div class="text-base">{{ $instructor->email }}</div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Details</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Created On</label>
                                    <div class="text-base">{{ $instructor->created_at->format('F j, Y') }}</div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                                    <div class="text-base">{{ $instructor->updated_at->format('F j, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('tenant.instructors.destroy', $instructor->id) }}" method="POST" class="mt-6 inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this instructor? This action cannot be undone.')">
                            <i class="fas fa-trash mr-2"></i> Delete Instructor
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
