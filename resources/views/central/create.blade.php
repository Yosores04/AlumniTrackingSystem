<x-central-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab navigation -->
            <div class="mb-6 bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="flex border-b">
                    <button id="create-tab-btn" class="px-6 py-3 w-1/2 text-center border-b-2 border-blue-500 text-blue-500 font-medium focus:outline-none">
                        Create New Tenant
                    </button>
                    <button id="list-tab-btn" class="px-6 py-3 w-1/2 text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium focus:outline-none">
                        Manage Tenants
                    </button>
                </div>
            </div>

            <!-- Create Tenant Section -->
            <div id="create-tab" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('Create New Tenant') }}
                    </h2>
                    
                    <!-- Authentication section -->
                    @guest
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="mb-2">Want to use your Google account information?</p>
                        <a href="{{ route('auth.google') }}" class="flex items-center justify-center bg-white hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded border border-gray-300 shadow-sm">
                            <img src="{{ asset('images/google.svg') }}" alt="Google" class="w-5 h-5 mr-2" onerror="this.src='https://www.google.com/favicon.ico'">
                            Sign in with Google
                        </a>
                    </div>
                    @endguest
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                        
                        @if(session('tenant_info'))
                            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                                <h3 class="font-bold text-lg mb-2">Tenant Information (Save these details)</h3>
                                <p><strong>Tenant ID:</strong> {{ session('tenant_info')['id'] }}</p>
                                <p><strong>Domain:</strong> {{ session('tenant_info')['domain'] }}</p>
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

                    @if(session('info'))
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Info:</strong>
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tenants.store') }}">
                        @csrf

                        <!-- Admin Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Admin Name</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Admin Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Admin Email</label>
                            <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Domain Prefix -->
                        <div class="mb-4">
                            <label for="domain_prefix" class="block text-gray-700 text-sm font-bold mb-2">Subdomain Prefix</label>
                            <div class="flex items-center">
                                <input type="text" name="domain_prefix" id="domain_prefix" class="py-2 px-3 bg-white rounded-l border focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 leading-8 transition-colors duration-200 ease-in-out flex-1">
                                <span class="bg-gray-200 py-2 px-3 rounded-r">.localhost:8000</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">
                                Only enter the subdomain prefix (e.g., "test1" will create "test1.localhost:8000").<br>
                                Only letters, numbers, and hyphens are allowed.
                            </p>
                            @error('domain_prefix')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Tenant
                            </button>
                        </div>
                    </form>

                    <!-- Example section -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-semibold text-lg text-gray-700 mb-2">Example:</h3>
                        <p>If you enter <span class="font-mono bg-gray-200 px-1 rounded">school1</span> in the field above, your tenant will be accessible at:</p>
                        <p class="font-mono text-blue-600 mt-1">http://school1.localhost:8000</p>
                    </div>
                </div>
            </div>

            <!-- List Tenants Section -->
            <div id="list-tab" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Manage Tenants') }}
                            </h2>
                            <a href="{{ route('tenants.fix-all-data') }}" class="ml-4 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">
                                Fix All Tenant Data
                            </a>
                        </div>
                        <div class="relative">
                            <input type="text" id="tenant-search" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Search tenants...">
                        </div>
                    </div>

                    <!-- Tenants Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Domain</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($tenants ?? [] as $tenant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $tenant->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        <div class="text-sm leading-5 font-medium text-gray-900">
                                            @if($tenant->domains && $tenant->domains->first())
                                                <a href="http://{{ $tenant->domains->first()->domain }}:8000" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                    {{ $tenant->domains->first()->domain }}:8000
                                                    <i class="fas fa-external-link-alt text-xs ml-1"></i>
                                                </a>
                                            @else
                                                <span class="text-gray-500">No domain</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $tenant->created_at ? $tenant->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        @php
                                            $status = $tenant->status ?? 'active';
                                            $statusClass = $status === 'active' ? 'bg-green-100 text-green-800' :
                                                          ($status === 'suspended' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        @php
                                            $planName = 'Free Plan';
                                            $planSlug = 'free';
                                            
                                            if ($tenant->plan_id) {
                                                // Get plan from database
                                                $plan = \App\Models\Plan::find($tenant->plan_id);
                                                if ($plan) {
                                                    $planName = $plan->name;
                                                    $planSlug = $plan->slug;
                                                }
                                            } elseif (!empty($tenant->subscription) && isset($tenant->subscription['plan'])) {
                                                // Use subscription json field if plan_id not set
                                                $planSlug = $tenant->subscription['plan'];
                                                // Get name from slug
                                                $plan = \App\Models\Plan::where('slug', $planSlug)->first();
                                                if ($plan) {
                                                    $planName = $plan->name;
                                                }
                                            }
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                                              data-plan-id="{{ $tenant->plan_id ?? '' }}" 
                                              data-plan-slug="{{ $planSlug }}">
                                            {{ $planName }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <!-- View button - Link to detailed tenant info -->
                                        <a href="/debug-tenants?id={{ $tenant->id }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="View details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Edit button -->
                                        <button class="edit-tenant-btn text-blue-600 hover:text-blue-900 mr-3" 
                                            data-tenant-id="{{ $tenant->id }}"
                                            title="Edit tenant">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <!-- Initialize Status button -->
                                        <a href="{{ route('tenants.initialize-status', $tenant->id) }}" 
                                            class="text-yellow-600 hover:text-yellow-900 mr-3" 
                                            title="Initialize Status">
                                            <i class="fas fa-sync"></i>
                                        </a>

                                        <!-- Delete button -->
                                        <button class="text-red-600 hover:text-red-900" 
                                            title="Delete tenant" 
                                            onclick="if(confirm('Are you sure you want to delete this tenant? This action cannot be undone.')) { deleteTenant('{{ $tenant->id }}'); }">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 text-center">
                                        No tenants found. <a href="#" class="text-blue-600 hover:text-blue-900" id="create-tenant-link">Create your first tenant</a>.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($tenants) && $tenants->hasPages())
                    <div class="mt-4">
                        {{ $tenants->links() }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Before the Edit Tenant Modal -->
            @php
                // Get plans from database
                $plans = \App\Models\Plan::all();
            @endphp

            <!-- Edit Tenant Modal -->
            <div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="px-6 py-4 border-b">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Tenant</h3>
                            <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="edit-tenant-form" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="tenant_id" id="edit-tenant-id">

                            <!-- Domain -->
                            <div class="mb-4">
                                <label for="edit-domain" class="block text-gray-700 text-sm font-bold mb-2">Domain</label>
                                <div class="flex items-center">
                                    <input type="text" name="domain_prefix" id="edit-domain" 
                                        class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                        required>
                                    <span class="bg-gray-200 py-2 px-3 rounded-r">.localhost</span>
                                </div>
                            </div>

                            <!-- Tenant Status -->
                            <div class="mb-4">
                                <label for="edit-status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                                <select name="status" id="edit-status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                            
                            <!-- Suspension Reason -->
                            <div id="suspension-reason-container" class="mb-4 hidden">
                                <label for="suspension_reason" class="block text-gray-700 text-sm font-bold mb-2">Suspension Reason</label>
                                <textarea name="suspension_reason" id="suspension_reason" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                <p class="text-gray-600 text-xs mt-1">
                                    Please provide a reason for suspending this tenant. This will be shown to the tenant.
                                </p>
                            </div>
                            
                            <!-- Tenant Subscription -->
                            <div class="mb-4">
                                <label for="edit-subscription" class="block text-gray-700 text-sm font-bold mb-2">Subscription Plan</label>
                                <select name="subscription_plan" id="edit-subscription" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->slug }}" data-plan-id="{{ $plan->id }}">{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-gray-600 text-xs mt-1">
                                    Changing plans takes effect immediately but preserves the billing cycle.
                                </p>
                            </div>
                            
                            <!-- Admin Message for Plan Update (Optional) -->
                            <div class="mb-4">
                                <label for="admin_message" class="block text-gray-700 text-sm font-bold mb-2">
                                    Additional Message to Tenant <span class="text-gray-500 font-normal">(Optional)</span>
                                </label>
                                <textarea name="admin_message" id="admin_message" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Add any special instructions or notes (optional). The system will automatically include detailed information about the plan upgrade."></textarea>
                                <p class="text-gray-600 text-xs mt-1">
                                    Leave blank to use the automatic notification, or add a personalized message to include with the plan details.
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" id="cancel-edit" class="mr-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const createTabBtn = document.getElementById('create-tab-btn');
            const listTabBtn = document.getElementById('list-tab-btn');
            const createTab = document.getElementById('create-tab');
            const listTab = document.getElementById('list-tab');
            const createTenantLink = document.getElementById('create-tenant-link');

            function setActiveTab(activeTab, inactiveTab, activeContent, inactiveContent) {
                // Show/hide content
                activeContent.classList.remove('hidden');
                inactiveContent.classList.add('hidden');
                
                // Style active tab
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                activeTab.classList.add('border-blue-500', 'text-blue-500');
                
                // Style inactive tab
                inactiveTab.classList.remove('border-blue-500', 'text-blue-500');
                inactiveTab.classList.add('border-transparent', 'text-gray-500');
            }

            // Check if query parameter exists to show the list tab by default
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'list') {
                setActiveTab(listTabBtn, createTabBtn, listTab, createTab);
                history.replaceState(null, '', '?tab=list');
            } else {
                setActiveTab(createTabBtn, listTabBtn, createTab, listTab);
                history.replaceState(null, '', '?tab=create');
            }

            createTabBtn.addEventListener('click', function() {
                setActiveTab(createTabBtn, listTabBtn, createTab, listTab);
                history.replaceState(null, '', '?tab=create');
            });

            listTabBtn.addEventListener('click', function() {
                setActiveTab(listTabBtn, createTabBtn, listTab, createTab);
                history.replaceState(null, '', '?tab=list');
            });

            if (createTenantLink) {
                createTenantLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    createTabBtn.click();
                });
            }

            // Search functionality
            const searchInput = document.getElementById('tenant-search');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('tbody tr');
                    
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Modal functionality
            const editModal = document.getElementById('edit-modal');
            const closeModal = document.getElementById('close-modal');
            const cancelEdit = document.getElementById('cancel-edit');

            if (closeModal) {
                closeModal.addEventListener('click', function() {
                    editModal.classList.add('hidden');
                });
            }

            if (cancelEdit) {
                cancelEdit.addEventListener('click', function() {
                    editModal.classList.add('hidden');
                });
            }

            // Suspension reason toggle based on status
            const statusSelect = document.getElementById('edit-status');
            const suspensionReasonContainer = document.getElementById('suspension-reason-container');
            
            if (statusSelect && suspensionReasonContainer) {
                // Show/hide suspension reason based on status selection
                statusSelect.addEventListener('change', function() {
                    if (this.value === 'suspended') {
                        suspensionReasonContainer.classList.remove('hidden');
                    } else {
                        suspensionReasonContainer.classList.add('hidden');
                    }
                });
                
                // Handle initial state when editing
                if (statusSelect.value === 'suspended') {
                    suspensionReasonContainer.classList.remove('hidden');
                }
            }

            // Edit tenant functionality
            const editButtons = document.querySelectorAll('.edit-tenant-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tenantId = this.getAttribute('data-tenant-id');
                    
                    // Find the tenant row
                    const row = this.closest('tr');
                    
                    // Get domain from link text (remove port)
                    const domainLink = row.querySelector('td:nth-child(2) a');
                    let domain = '';
                    
                    if (domainLink) {
                        // Extract domain without port
                        const domainWithPort = domainLink.textContent.trim();
                        domain = domainWithPort.replace(':8000', '');
                        
                        // Extract domain prefix (remove .localhost)
                        const domainPrefix = domain.replace('.localhost', '');
                        document.getElementById('edit-domain').value = domainPrefix;
                    }
                    
                    // Get status from cell
                    const statusElement = row.querySelector('td:nth-child(4) span');
                    if (statusElement) {
                        const status = statusElement.textContent.trim();
                        document.getElementById('edit-status').value = status;
                        
                        // Show/hide suspension reason field
                        if (status === 'suspended') {
                            document.getElementById('suspension-reason-container').classList.remove('hidden');
                        } else {
                            document.getElementById('suspension-reason-container').classList.add('hidden');
                        }
                    }
                    
                    // Get plan from cell
                    const planElement = row.querySelector('td:nth-child(5) span');
                    if (planElement) {
                        const planSlug = planElement.getAttribute('data-plan-slug');
                        document.getElementById('edit-subscription').value = planSlug;
                    }
                    
                    // Update form action URL and tenant ID
                    document.getElementById('edit-tenant-form').action = `/tenants/${tenantId}`;
                    document.getElementById('edit-tenant-id').value = tenantId;
                    
                    // Show edit modal
                    document.getElementById('edit-modal').classList.remove('hidden');
                });
            });
            
            // Form submission with debugging
            const editForm = document.getElementById('edit-tenant-form');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Form action:', this.action);
                    console.log('Form method:', this.method);
                    console.log('Tenant ID:', document.getElementById('edit-tenant-id').value);
                    console.log('Domain:', document.getElementById('edit-domain').value);
                    console.log('Status:', document.getElementById('edit-status').value);
                    this.submit();
                });
            }
            
            // Delete tenant functionality
            window.deleteTenant = function(tenantId) {
                // Create a form to submit a DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/tenants/${tenantId}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            };
        });
    </script>
</x-central-app-layout>