<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Subscription Plans') }}
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

                    <div class="mb-6 text-center">
                        <h3 class="text-2xl font-bold text-gray-800">Choose the Right Plan for Your Institution</h3>
                        <p class="mt-2 text-gray-600">All plans include our core alumni tracking features</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                        @foreach ($plans as $plan)
                            <div class="border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-6 flex flex-col {{ $plan->slug === 'premium' ? 'border-accent' : '' }}">
                                @if ($plan->slug === 'premium')
                                    <div class="bg-accent text-white text-center py-1 rounded-t-lg absolute top-0 left-0 right-0 -mt-4 mx-auto w-32">
                                        Most Popular
                                    </div>
                                @endif
                                
                                <h3 class="text-xl font-bold text-center mb-4">{{ $plan->name }}</h3>
                                
                                <div class="text-center mb-6">
                                    @if ($plan->monthly_price > 0)
                                        <span class="text-4xl font-bold">${{ number_format($plan->monthly_price, 2) }}</span>
                                        <span class="text-gray-600">/month</span>
                                        
                                        <div class="mt-1 text-sm text-gray-600">
                                            or ${{ number_format($plan->annual_price / 12, 2) }}/month billed annually
                                            <span class="text-accent font-medium">(Save {{ $plan->getAnnualDiscountPercentage() }}%)</span>
                                        </div>
                                    @else
                                        <span class="text-4xl font-bold">Free</span>
                                        <div class="mt-1 text-sm text-gray-600">forever</div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow">
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            @if ($plan->hasUnlimitedAlumni())
                                                <span>Unlimited alumni records</span>
                                            @else
                                                <span>Up to {{ number_format($plan->max_alumni) }} alumni records</span>
                                            @endif
                                        </li>
                                        
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            @if ($plan->hasUnlimitedInstructors())
                                                <span>Unlimited instructor accounts</span>
                                            @else
                                                <span>Up to {{ $plan->max_instructors }} instructor account{{ $plan->max_instructors > 1 ? 's' : '' }}</span>
                                            @endif
                                        </li>
                                        
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            @if ($plan->has_custom_fields)
                                                <span>Custom fields</span>
                                            @else
                                                <span>Basic profiles</span>
                                            @endif
                                        </li>
                                        
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 {{ $plan->has_advanced_analytics ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="{{ $plan->has_advanced_analytics ? '' : 'text-gray-500' }}">Advanced analytics</span>
                                        </li>
                                        
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 {{ $plan->has_integrations ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="{{ $plan->has_integrations ? '' : 'text-gray-500' }}">API access</span>
                                        </li>
                                        
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 {{ $plan->has_job_board ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="{{ $plan->has_job_board ? '' : 'text-gray-500' }}">Job board</span>
                                        </li>
                                        
                                        <li class="flex items-start">
                                            <svg class="h-5 w-5 {{ $plan->has_custom_branding ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="{{ $plan->has_custom_branding ? '' : 'text-gray-500' }}">Custom branding</span>
                                        </li>
                                        
                                        <li class="flex items-start">
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
                                
                                <div class="mt-8">
                                    <form action="{{ route('plans.subscribe', $plan) }}" method="POST" class="space-y-4">
                                        @csrf
                                        
                                        @if ($plan->monthly_price > 0)
                                            <div class="flex justify-center space-x-4">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="billing_cycle" value="monthly" checked class="form-radio h-4 w-4 text-accent">
                                                    <span class="ml-2">Monthly</span>
                                                </label>
                                                
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="billing_cycle" value="annual" class="form-radio h-4 w-4 text-accent">
                                                    <span class="ml-2">Annual <span class="text-xs text-accent">(Save {{ $plan->getAnnualDiscountPercentage() }}%)</span></span>
                                                </label>
                                            </div>
                                        @else
                                            <input type="hidden" name="billing_cycle" value="monthly">
                                        @endif
                                        
                                        <button type="submit" class="w-full py-2 px-4 {{ $plan->slug === 'premium' ? 'bg-accent hover:bg-accent-dark' : 'bg-primary hover:bg-primary-dark' }} text-white rounded-md transition-colors duration-300">
                                            @if ($plan->monthly_price > 0)
                                                Choose {{ $plan->name }}
                                            @else
                                                Get Started Free
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-12 text-center">
                        <h3 class="text-xl font-bold text-gray-800">Have Questions?</h3>
                        <p class="mt-2 text-gray-600">Contact our sales team at <a href="mailto:sales@alumni-tracker.com" class="text-accent hover:underline">sales@alumni-tracker.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 