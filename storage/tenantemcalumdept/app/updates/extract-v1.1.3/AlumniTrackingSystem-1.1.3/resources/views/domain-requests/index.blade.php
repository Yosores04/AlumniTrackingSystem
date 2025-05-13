<x-central-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Manage Domain Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                
                @if(session('tenant_info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                        <h3 class="font-bold text-lg mb-2">Tenant Information (Save these details)</h3>
                        <p><strong>Tenant ID:</strong> {{ session('tenant_info')['domain'] }}</p>
                        <p><strong>Login URL:</strong> {{ session('tenant_info')['login_url'] }}</p>
                        <p><strong>Admin Email:</strong> {{ session('tenant_info')['email'] }}</p>
                        <p><strong>Admin Password:</strong> {{ session('tenant_info')['password'] }}</p>
                        <p class="mt-2 text-sm">This password will not be shown again. Please make sure to save it.</p>
                    </div>
                @endif
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Pending Requests Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                        {{ __('Pending Requests') }}
                    </h2>

                    @if($pendingRequests->isEmpty())
                        <div class="bg-gray-50 p-4 rounded text-gray-600">
                            No pending domain requests found.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($pendingRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $request->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 font-medium text-gray-900">
                                                {{ $request->admin_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $request->admin_email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-blue-600">
                                            {{ $request->domain_prefix }}.localhost
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $request->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="approveRequest({{ $request->id }})" class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full hover:bg-green-200">
                                                    <i class="fas fa-check mr-1"></i> Approve
                                                </button>
                                                <button onclick="showRejectModal({{ $request->id }})" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full hover:bg-red-200 ml-2">
                                                    <i class="fas fa-times mr-1"></i> Reject
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                        {{ __('Processed Requests') }}
                    </h2>

                    @if($processedRequests->isEmpty())
                        <div class="bg-gray-50 p-4 rounded text-gray-600">
                            No processed domain requests found.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Processed At</th>
                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($processedRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            {{ $request->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 font-medium text-gray-900">
                                                {{ $request->admin_name }}
                                            </div>
                                            <div class="text-sm leading-5 text-gray-500">
                                                {{ $request->admin_email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-blue-600">
                                            {{ $request->domain_prefix }}.localhost
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            @if($request->status === 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                                <button onclick="showRejectionReason('{{ addslashes($request->rejection_reason) }}')" class="text-xs text-gray-500 hover:text-gray-700 ml-1">
                                                    (View reason)
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                            @if($request->status === 'approved')
                                                {{ $request->approved_at ? $request->approved_at->format('M d, Y H:i') : 'N/A' }}
                                            @else
                                                {{ $request->rejected_at ? $request->rejected_at->format('M d, Y H:i') : 'N/A' }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-center">
                                            @if($request->status === 'approved')
                                                <a href="http://{{ $request->domain_prefix }}.localhost:8000" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Visit Domain
                                                    </span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $processedRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Reject Domain Request</h3>
                    <button id="close-reject-modal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="reject-form" method="POST">
                    @csrf
                    @method('POST')

                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-gray-700 text-sm font-bold mb-2">Reason for Rejection</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        <p class="text-gray-600 text-xs mt-1">Please provide a clear reason for rejection. This will be sent to the requester.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="cancel-reject" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancel
                        </button>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Reject Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div id="rejection-reason-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Rejection Reason</h3>
                    <button id="close-reason-modal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <p id="rejection-reason-text" class="text-gray-700"></p>
                <div class="flex justify-end mt-4">
                    <button type="button" id="close-reason-btn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function approveRequest(id) {
            if (confirm('Are you sure you want to approve this domain request?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/domain-requests/${id}/approve`;
                form.style.display = 'none';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function showRejectModal(id) {
            document.getElementById('reject-form').action = `/domain-requests/${id}/reject`;
            document.getElementById('reject-modal').classList.remove('hidden');
        }
        
        function showRejectionReason(reason) {
            document.getElementById('rejection-reason-text').textContent = reason;
            document.getElementById('rejection-reason-modal').classList.remove('hidden');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Close reject modal
            document.getElementById('close-reject-modal').addEventListener('click', function() {
                document.getElementById('reject-modal').classList.add('hidden');
            });
            
            document.getElementById('cancel-reject').addEventListener('click', function() {
                document.getElementById('reject-modal').classList.add('hidden');
            });
            
            // Close reason modal
            document.getElementById('close-reason-modal').addEventListener('click', function() {
                document.getElementById('rejection-reason-modal').classList.add('hidden');
            });
            
            document.getElementById('close-reason-btn').addEventListener('click', function() {
                document.getElementById('rejection-reason-modal').classList.add('hidden');
            });
        });
    </script>
</x-central-app-layout>
