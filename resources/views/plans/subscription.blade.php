<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Your Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
                        <div class="flex flex-col md:flex-row justify-between">
                            <div class="mb-4 md:mb-0">
                                <h3 class="text-2xl font-bold text-gray-800">
                                    {{ $plan->name }} Plan
                                </h3>
                                
                                <div class="mt-4">
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">Billing Cycle:</span> 
                                        {{ ucfirst($tenant->billing_cycle) }}
                                    </div>
                                    
                                    @if ($tenant->plan_expires_at)
                                        <div class="text-sm text-gray-600 mt-2">
                                            <span class="font-medium">Next Billing Date:</span> 
                                            {{ $tenant->plan_expires_at->format('F d, Y') }}
                                        </div>
                                    @endif
                                    
                                    <div class="text-sm text-gray-600 mt-2">
                                        <span class="font-medium">Amount:</span> 
                                        @if ($plan->slug === 'free')
                                            Free
                                        @else
                                            ${{ $tenant->billing_cycle === 'monthly' ? 
                                                number_format($plan->monthly_price, 2) . '/month' : 
                                                number_format($plan->annual_price, 2) . '/year' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col space-y-3">
                                @if ($plan->slug !== 'free')
                                    <form action="{{ route('plans.cancel') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 border border-red-600 text-red-600 rounded-md hover:bg-red-50" onclick="return confirm('Are you sure you want to cancel your subscription? You will still have access until the end of your billing period.')">
                                            Cancel Subscription
                                        </button>
                                    </form>
                                @endif
                                
                                @if ($plan->slug !== 'premium')
                                    <a href="{{ route('plans.index') }}" class="px-4 py-2 bg-accent text-white rounded-md hover:bg-accent-dark text-center">
                                        Upgrade Plan
                                    </a>
                                @endif
                                
                                @if ($tenant->billing_cycle === 'monthly' && $plan->slug !== 'free')
                                    <form action="{{ route('plans.subscribe', $plan) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="billing_cycle" value="annual">
                                        <button type="submit" class="px-4 py-2 border border-accent text-accent rounded-md hover:bg-accent-50">
                                            Switch to Annual (Save {{ $plan->getAnnualDiscountPercentage() }}%)
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-800 mb-4">Plan Details</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-gray-700 mb-2">Resources</h5>
                                <ul class="space-y-2">
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        @if ($plan->hasUnlimitedAlumni())
                                            <span>Unlimited alumni records</span>
                                        @else
                                            <span>Up to {{ number_format($plan->max_alumni) }} alumni records</span>
                                        @endif
                                    </li>
                                    
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        @if ($plan->hasUnlimitedInstructors())
                                            <span>Unlimited instructor accounts</span>
                                        @else
                                            <span>Up to {{ $plan->max_instructors }} instructor account{{ $plan->max_instructors > 1 ? 's' : '' }}</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            
                            <div>
                                <h5 class="font-medium text-gray-700 mb-2">Features</h5>
                                <ul class="space-y-2">
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        @if ($plan->has_custom_fields)
                                            <span>Custom fields</span>
                                        @else
                                            <span>Basic profiles</span>
                                        @endif
                                    </li>
                                    
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 {{ $plan->has_advanced_analytics ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="{{ $plan->has_advanced_analytics ? '' : 'text-gray-500' }}">Advanced analytics</span>
                                    </li>
                                    
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 {{ $plan->has_job_board ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="{{ $plan->has_job_board ? '' : 'text-gray-500' }}">Job board</span>
                                    </li>
                                    
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 {{ $plan->has_custom_branding ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="{{ $plan->has_custom_branding ? '' : 'text-gray-500' }}">Custom branding</span>
                                    </li>
                                    
                                    <li class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>
                                            @if ($plan->support_level === 'priority')
                                                Priority support
                                            @elseif ($plan->support_level === 'email')
                                                Email support
                                            @else
                                                Community support
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 