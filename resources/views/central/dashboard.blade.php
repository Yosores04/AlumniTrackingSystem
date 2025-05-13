<x-central-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Central Administration</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="bg-primary-50 border border-primary-200 rounded-lg p-6 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="bg-primary-100 p-3 rounded-lg">
                                    <i class="fas fa-building text-primary"></i>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">Tenant Management</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Create and manage tenant accounts in the system.</p>
                            <a href="{{ route('tenants.create') }}" class="text-primary hover:text-primary-dark font-medium">
                                Manage Tenants <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="bg-indigo-100 p-3 rounded-lg">
                                    <i class="fas fa-tags text-indigo-600"></i>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">Subscription Plans</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Manage subscription plans for tenants.</p>
                            <a href="{{ route('admin.plans.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                Manage Plans <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 hover:shadow-md transition duration-300">
                            <div class="flex items-center mb-3">
                                <div class="bg-amber-100 p-3 rounded-lg">
                                    <i class="fas fa-globe text-amber-600"></i>
                                </div>
                                <h4 class="ml-3 text-lg font-semibold text-gray-900">Domain Requests</h4>
                            </div>
                            <p class="text-gray-600 mb-4">Review and manage custom domain requests.</p>
                            <a href="{{ route('domain-requests.index') }}" class="text-amber-600 hover:text-amber-800 font-medium">
                                Manage Domains <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">System Status</h3>
                        <div class="bg-white border rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <h5 class="font-medium text-gray-500 mb-2">Total Tenants</h5>
                                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Tenant::count() }}</p>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-500 mb-2">Active Tenants</h5>
                                    <p class="text-3xl font-bold text-green-600">{{ \App\Models\Tenant::where('status', 'active')->count() }}</p>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-500 mb-2">Suspended</h5>
                                    <p class="text-3xl font-bold text-red-600">{{ \App\Models\Tenant::where('status', 'suspended')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-central-app-layout> 