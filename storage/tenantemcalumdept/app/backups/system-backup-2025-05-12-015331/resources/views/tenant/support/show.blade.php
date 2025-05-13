@extends($layout ?? 'layouts.tenant')

@section('title', 'Support Ticket #' . $ticket->id)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('support.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Status Banner -->
    @if($ticket->status === 'closed')
    <div class="bg-gray-100 border-l-4 border-gray-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-gray-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-gray-700">
                    This ticket is closed. 
                    @if(!Auth::user()->isAdmin() && !Auth::user()->isInstructor())
                        If you still need help, you can add a new response below to reopen it.
                    @else
                        As a staff member, you can still respond if needed.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @elseif($ticket->status === 'resolved')
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">
                    This ticket has been marked as resolved. 
                    @if(!Auth::user()->isAdmin() && !Auth::user()->isInstructor())
                        If your issue has been addressed, no further action is needed. If you still need help, you can add a new response below to reopen the ticket.
                    @else
                        As a staff member, you can close it if no further action is needed.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Ticket Details -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Ticket Information</h2>
                <div class="space-y-2">
                    <p><span class="font-semibold">Status:</span> {!! $ticket->status_badge !!}</p>
                    <p><span class="font-semibold">Priority:</span> {!! $ticket->priority_badge !!}</p>
                    <p><span class="font-semibold">Created by:</span> {{ $ticket->user->name }}</p>
                    <p><span class="font-semibold">Created on:</span> {{ $ticket->formatted_created_date }}</p>
                    @if($ticket->attachment_path)
                    <p>
                        <span class="font-semibold">Attachment:</span> 
                        <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank" class="text-secondary hover:text-primary">
                            <i class="fas fa-paperclip mr-1"></i> View Attachment
                        </a>
                    </p>
                    @endif
                </div>
                <div class="mt-4">
                    <a href="{{ route('support.edit', $ticket->id) }}" class="inline-flex items-center bg-secondary hover:bg-primary text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i> Edit Ticket
                    </a>
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
                <div class="bg-gray-50 p-4 rounded border border-gray-200">
                    <p class="whitespace-pre-line">{{ $ticket->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Responses -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Responses</h2>

        <div class="space-y-6">
            @if($ticket->responses->count() > 0)
                @foreach($ticket->responses as $response)
                <div class="{{ $response->is_staff_reply ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-gray-50 border-l-4 border-gray-300' }} p-4 rounded">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">
                                {{ $response->user->name }}
                                @if($response->is_staff_reply)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Support Team
                                </span>
                                @elseif($response->user_id === $ticket->user_id)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Ticket Creator
                                </span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $response->formatted_created_date }}</p>
                        </div>
                        @if($response->attachment_path)
                        <a href="{{ asset('storage/' . $response->attachment_path) }}" target="_blank" class="text-secondary hover:text-primary text-sm">
                            <i class="fas fa-paperclip mr-1"></i> View Attachment
                        </a>
                        @endif
                    </div>
                    <div class="mt-2">
                        <p class="whitespace-pre-line">{{ $response->message }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="bg-gray-50 p-4 rounded text-center">
                    <p class="text-gray-500">No responses yet. {{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Please provide assistance below.' : 'A support team member will respond soon.' }}</p>
                </div>
            @endif
        </div>

        <!-- Add Response Form -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                {{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Add Support Response' : 'Add Reply' }}
            </h3>
            
            @if(Auth::user()->isAdmin() || Auth::user()->isInstructor())
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Your response will be sent to the ticket creator via email. 
                            Responding to an open ticket will automatically set its status to "In Progress".
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

            <form action="{{ route('support.response', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea name="message" id="message" rows="4" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50"
                        placeholder="{{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Write your support response here...' : 'Add additional information or ask follow-up questions...' }}">{{ old('message') }}</textarea>
                </div>
                
                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1">Attachment (Optional)</label>
                    <input type="file" name="attachment" id="attachment"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">You can attach a file (screenshot, document, etc.) to your response. Max size: 10MB.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-secondary hover:bg-primary text-white font-bold py-2 px-4 rounded">
                        {{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Post Support Response' : 'Submit Reply' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 