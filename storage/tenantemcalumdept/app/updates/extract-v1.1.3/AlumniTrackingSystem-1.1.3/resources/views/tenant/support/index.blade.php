@extends($layout ?? 'layouts.tenant')

@section('title', 'Support Tickets')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Support Tickets</h1>
        <a href="{{ route('support.create') }}" class="@if($layout == 'layouts.alumni') bg-blue-600 hover:bg-blue-700 @else bg-secondary hover:bg-primary @endif text-white font-bold py-2 px-4 rounded">
            {{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Create Ticket' : 'Request Support' }}
        </a>
    </div>
    
    @if(Auth::user()->isAdmin() || Auth::user()->isInstructor())
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    As a staff member, you can view and respond to all support tickets. Regular users can only create tickets and view their own.
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
                    Need help? Create a support ticket by clicking "Request Support", and our team will respond as soon as possible.
                    You'll receive email notifications when your ticket receives a response.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form action="{{ route('support.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50"
                    placeholder="Search tickets...">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50">
                    <option value="">All Statuses</option>
                    <option value="open" {{ ($filters['status'] ?? '') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ ($filters['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ ($filters['status'] ?? '') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ ($filters['status'] ?? '') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" id="priority" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50">
                    <option value="">All Priorities</option>
                    <option value="low" {{ ($filters['priority'] ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ ($filters['priority'] ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ ($filters['priority'] ?? '') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ ($filters['priority'] ?? '') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-secondary hover:bg-primary text-white font-bold py-2 px-4 rounded mr-2">
                    Filter
                </button>
                <a href="{{ route('support.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tickets List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isInstructor())
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                    @endif
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if($tickets->count() > 0)
                    @foreach($tickets as $ticket)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">#{{ $ticket->id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $ticket->subject }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($ticket->description, 60) }}</div>
                        </td>
                        @if(Auth::user()->isAdmin() || Auth::user()->isInstructor())
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $ticket->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{!! $ticket->status_badge !!}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{!! $ticket->priority_badge !!}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $ticket->formatted_created_date }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('support.show', $ticket->id) }}" class="text-secondary hover:text-primary">View</a>
                            <a href="{{ route('support.edit', $ticket->id) }}" class="ml-3 text-gray-600 hover:text-gray-900">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 7 : 6 }}" class="px-6 py-4 text-center text-gray-500">
                            No support tickets found. <a href="{{ route('support.create') }}" class="@if($layout == 'layouts.alumni') text-blue-600 hover:text-blue-800 font-semibold @else text-secondary hover:text-primary @endif">
                            {{ Auth::user()->isAdmin() || Auth::user()->isInstructor() ? 'Create a new ticket' : 'Request support' }}</a>.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tickets->appends($filters)->links() }}
    </div>
</div>
@endsection 