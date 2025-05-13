@extends($layout ?? 'layouts.tenant')

@section('title', 'Create Support Ticket')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('support.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">
            {{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Create Support Ticket' : 'Request Support' }}
        </h1>
    </div>

    @if(Auth::user()->isAdmin() || Auth::user()->isInstructor())
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Note for Staff:</strong> Typically, support tickets should be created by regular users seeking help.
                    As a staff member, you would normally respond to tickets rather than create them.
                    However, you can create a ticket if needed for testing purposes or on behalf of a user.
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Describe your issue in detail, and our support team will respond as soon as possible.
                    You'll receive an email notification when your ticket receives a response.
                </p>
            </div>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
        <p class="font-bold">Please fix the following errors:</p>
        <ul class="list-disc ml-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50"
                    placeholder="Brief summary of your issue">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="5" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50"
                    placeholder="Please describe your issue in detail. Include any error messages, steps to reproduce, and what you've already tried.">{{ old('description') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Please provide as much detail as possible about your issue.</p>
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                <select name="priority" id="priority" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50">
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority') == 'medium' || old('priority') == null ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    <strong>Low</strong>: Minor issue, no urgency <br>
                    <strong>Medium</strong>: Standard issue that needs attention <br>
                    <strong>High</strong>: Significant problem affecting usage <br>
                    <strong>Urgent</strong>: Critical issue requiring immediate attention
                </p>
            </div>
            
            <div class="mb-4">
                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Attachment (Optional)</label>
                <input type="file" name="attachment" id="attachment"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50">
                <p class="text-sm text-gray-500 mt-1">You can attach a file (screenshot, document, etc.) to help explain your issue. Max size: 10MB.</p>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('support.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-secondary hover:bg-primary text-white font-bold py-2 px-4 rounded">
                    {{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Create Ticket' : 'Submit Request' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 