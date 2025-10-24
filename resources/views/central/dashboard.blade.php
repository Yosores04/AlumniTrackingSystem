<x-central-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-display font-bold text-buksu-navy-900 mb-2">Central Administration</h1>
                <p class="text-gray-600">Manage tenants, subscriptions, and system settings</p>
            </div>
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-buksu-navy-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ \App\Models\Tenant::count() }}</div>
                    <div class="stat-label">Total Tenants</div>
                </div>
                
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-success-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value text-success-600">{{ \App\Models\Tenant::where('status', 'active')->count() }}</div>
                    <div class="stat-label">Active Tenants</div>
                </div>
                
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-error-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-error-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value text-error-600">{{ \App\Models\Tenant::where('status', 'suspended')->count() }}</div>
                    <div class="stat-label">Suspended</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tenant Management Card -->
                <div class="card group hover:shadow-soft-lg">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-buksu-navy-50 flex items-center justify-center group-hover:bg-buksu-navy-100 transition-colors">
                                <svg class="w-7 h-7 text-buksu-navy-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900 mb-2">Tenant Management</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Create and manage tenant accounts in the system.</p>
                        <a href="{{ route('tenants.create') }}" class="inline-flex items-center text-buksu-navy-700 hover:text-buksu-navy-900 font-medium group">
                            Manage Tenants
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Subscription Plans Card -->
                <div class="card group hover:shadow-soft-lg">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-buksu-gold-50 flex items-center justify-center group-hover:bg-buksu-gold-100 transition-colors">
                                <svg class="w-7 h-7 text-buksu-gold-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900 mb-2">Subscription Plans</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Manage subscription plans for tenants.</p>
                        <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center text-buksu-navy-700 hover:text-buksu-navy-900 font-medium group">
                            Manage Plans
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Domain Requests Card -->
                <div class="card group hover:shadow-soft-lg">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-buksu-accent-50 flex items-center justify-center group-hover:bg-buksu-accent-100 transition-colors">
                                <svg class="w-7 h-7 text-buksu-accent-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-display font-semibold text-buksu-navy-900 mb-2">Domain Requests</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Review and manage custom domain requests.</p>
                        <a href="{{ route('domain-requests.index') }}" class="inline-flex items-center text-buksu-navy-700 hover:text-buksu-navy-900 font-medium group">
                            Manage Domains
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-central-app-layout> 