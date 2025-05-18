<x-central-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md transition-all duration-300" role="alert">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                
                @if(session('tenant_info'))
                    <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center mb-3">
                            <svg class="h-8 w-8 text-blue-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-xl font-bold text-blue-900">Tenant Information</h3>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-blue-100 mb-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Tenant ID</p>
                                    <p class="font-medium">{{ session('tenant_info')['domain'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Login URL</p>
                                    <p class="font-medium text-blue-600">{{ session('tenant_info')['login_url'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Admin Email</p>
                                    <p class="font-medium">{{ session('tenant_info')['email'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Admin Password</p>
                                    <p class="font-medium font-mono bg-gray-100 p-1 rounded">{{ session('tenant_info')['password'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center text-sm text-amber-700 bg-amber-50 p-3 rounded-lg">
                            <svg class="h-5 w-5 text-amber-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>This password will not be shown again. Please save it in a secure location.</span>
                        </div>
                    </div>
                @endif
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-red-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pending Requests Section -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-8 transition-all duration-300 hover:shadow-xl">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h2 class="font-bold text-xl text-white">
                        Pending Requests
                    </h2>
                </div>
                <div class="p-6 border-b border-gray-200">
                    @if($pendingRequests->isEmpty())
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <svg class="h-16 w-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500 text-lg">No pending domain requests found</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($pendingRequests as $request)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            #{{ $request->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 font-medium text-gray-900">
                                                {{ $request->admin_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $request->admin_email }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-blue-800">
                                                <svg class="h-3 w-3 text-blue-500 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                                {{ $request->domain_prefix }}.localhost:8000
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $request->created_at->format('M d, Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                            <div class="flex space-x-2">
                                                <button onclick="approveRequest({{ $request->id }})" class="flex items-center justify-center px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-150">
                                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Approve
                                                </button>
                                                <button onclick="showRejectModal({{ $request->id }})" class="flex items-center justify-center px-3 py-1.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
                                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Processed Requests Section -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6 transition-all duration-300 hover:shadow-xl">
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4">
                    <h2 class="font-bold text-xl text-white">
                        Processed Requests
                    </h2>
                </div>
                <div class="p-6 border-b border-gray-200">
                    @if($processedRequests->isEmpty())
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <svg class="h-16 w-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 14l2 2 4-4" />
                            </svg>
                            <p class="text-gray-500 text-lg">No processed domain requests found</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Processed At</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($processedRequests as $request)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            #{{ $request->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 font-medium text-gray-900">
                                                {{ $request->admin_name }}
                                            </div>
                                            <div class="text-sm leading-5 text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="h-3 w-3 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $request->admin_email }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-blue-800">
                                                <svg class="h-3 w-3 text-blue-500 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                                {{ $request->domain_prefix }}.localhost
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            @if($request->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Approved
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Rejected
                                                </span>
                                                <button onclick="showRejectionReason('{{ addslashes($request->rejection_reason) }}')" class="ml-2 text-xs text-gray-500 hover:text-gray-700 underline transition-colors duration-200">
                                                    View reason
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                @if($request->status === 'approved')
                                                    {{ $request->approved_at ? $request->approved_at->format('M d, Y H:i') : 'N/A' }}
                                                @else
                                                    {{ $request->rejected_at ? $request->rejected_at->format('M d, Y H:i') : 'N/A' }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-center">
                                            @if($request->status === 'approved')
                                                <a href="http://{{ $request->domain_prefix }}.localhost:8000" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    Visit Domain
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $processedRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Confirmation Modal -->
    <div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-t-lg px-6 py-4">
                <h3 class="text-lg font-medium text-white">Confirm Approval</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-6 text-emerald-700 bg-emerald-50 p-4 rounded-lg">
                    <svg class="h-6 w-6 text-emerald-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>Are you sure you want to approve this domain request? This will create a new tenant and cannot be easily undone.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button id="cancel-approve" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors duration-150">
                        Cancel
                    </button>
                    <button id="confirm-approve" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-150">
                        Yes, Approve
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-t-lg px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-white">Reject Domain Request</h3>
                    <button id="close-reject-modal" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="reject-form" method="POST">
                    @csrf
                    @method('POST')

                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-gray-700 text-sm font-bold mb-2">Reason for Rejection</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition-colors" placeholder="Please provide a clear reason for rejection..." required></textarea>
                        <p class="text-gray-500 text-xs mt-1">This reason will be sent to the requester via email.</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancel-reject" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors duration-150">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150">
                            Reject Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div id="rejection-reason-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-t-lg px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-white">Rejection Reason</h3>
                    <button id="close-reason-modal" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-4">
                    <p id="rejection-reason-text" class="text-gray-700 whitespace-pre-line"></p>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="close-reason-btn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-colors duration-150">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables to store the current domain request ID
        let currentRequestId = null;
        
        // Function to show and handle approval confirmation
        function approveRequest(id) {
            currentRequestId = id;
            const modal = document.getElementById('approve-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        // Set up event handlers when the document is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Approval modal
            const approveModal = document.getElementById('approve-modal');
            const cancelApproveBtn = document.getElementById('cancel-approve');
            const confirmApproveBtn = document.getElementById('confirm-approve');
            
            cancelApproveBtn.addEventListener('click', function() {
                approveModal.classList.add('hidden');
                approveModal.classList.remove('flex');
            });
            
            confirmApproveBtn.addEventListener('click', function() {
                if (currentRequestId) {
                    // Submit the form for approval
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/domain-requests/${currentRequestId}/approve`;
                    form.style.display = 'none';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
            
            // Reject modal
            const rejectModal = document.getElementById('reject-modal');
            const closeRejectModalBtn = document.getElementById('close-reject-modal');
            const cancelRejectBtn = document.getElementById('cancel-reject');
            
            function closeRejectModal() {
                rejectModal.classList.add('hidden');
                rejectModal.classList.remove('flex');
            }
            
            if (closeRejectModalBtn) {
                closeRejectModalBtn.addEventListener('click', closeRejectModal);
            }
            
            if (cancelRejectBtn) {
                cancelRejectBtn.addEventListener('click', closeRejectModal);
            }
            
            // Rejection reason modal
            const reasonModal = document.getElementById('rejection-reason-modal');
            const closeReasonModalBtn = document.getElementById('close-reason-modal');
            const closeReasonBtn = document.getElementById('close-reason-btn');
            
            function closeReasonModal() {
                reasonModal.classList.add('hidden');
                reasonModal.classList.remove('flex');
            }
            
            if (closeReasonModalBtn) {
                closeReasonModalBtn.addEventListener('click', closeReasonModal);
            }
            
            if (closeReasonBtn) {
                closeReasonBtn.addEventListener('click', closeReasonModal);
            }
        });
        
        // Function to show rejection modal
        function showRejectModal(id) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            
            if (modal && form) {
                form.action = `/domain-requests/${id}/reject`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                // Focus on the textarea
                setTimeout(() => {
                    document.getElementById('rejection_reason').focus();
                }, 100);
            }
        }
        
        // Function to show rejection reason
        function showRejectionReason(reason) {
            const modal = document.getElementById('rejection-reason-modal');
            const reasonText = document.getElementById('rejection-reason-text');
            
            if (modal && reasonText) {
                reasonText.textContent = reason;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
    </script>
</x-central-app-layout>
