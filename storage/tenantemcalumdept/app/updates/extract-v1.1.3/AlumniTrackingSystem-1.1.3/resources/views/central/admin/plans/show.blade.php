<x-central-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Plan Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $plan->name }}</h3>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.plans.edit', $plan->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Edit Plan
                            </a>
                            <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="text-sm text-gray-500 mb-2">Status</div>
                        <div>
                            @if ($plan->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Plan Information</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-500">Name</div>
                                        <div class="text-sm font-medium">{{ $plan->name }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Slug</div>
                                        <div class="text-sm font-medium">{{ $plan->slug }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Monthly Price</div>
                                        <div class="text-sm font-medium">${{ number_format($plan->monthly_price, 2) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Annual Price</div>
                                        <div class="text-sm font-medium">${{ number_format($plan->annual_price, 2) }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Support Level</div>
                                        <div class="text-sm font-medium capitalize">{{ $plan->support_level }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Limits</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs text-gray-500">Max Alumni</div>
                                        <div class="text-sm font-medium">{{ $plan->max_alumni ? $plan->max_alumni : 'Unlimited' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Max Instructors</div>
                                        <div class="text-sm font-medium">{{ $plan->max_instructors ? $plan->max_instructors : 'Unlimited' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $plan->description ?: 'No description provided.' }}</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Features</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <li class="flex items-center">
                                    @if($plan->has_custom_fields)
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                    <span class="ml-2 text-sm">Custom Profile Fields</span>
                                </li>
                                <li class="flex items-center">
                                    @if($plan->has_advanced_analytics)
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                    <span class="ml-2 text-sm">Advanced Analytics</span>
                                </li>
                                <li class="flex items-center">
                                    @if($plan->has_integrations)
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                    <span class="ml-2 text-sm">Third-party Integrations</span>
                                </li>
                                <li class="flex items-center">
                                    @if($plan->has_job_board)
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                    <span class="ml-2 text-sm">Job Board</span>
                                </li>
                                <li class="flex items-center">
                                    @if($plan->has_custom_branding)
                                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                    <span class="ml-2 text-sm">Custom Branding</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-central-app-layout> 