<x-suspended-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Domain Suspended') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center">
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-xl">Domain Suspended</p>
                                    <p class="text-md">{{ $reason }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Domain Details</h3>
                            <p class="mt-1 text-sm text-gray-600">Your Domain is currently {{ $status }}.</p>
                            @if($suspended_at)
                                <p class="mt-1 text-sm text-gray-600">Suspended on: {{ $suspended_at }}</p>
                            @endif
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-md font-medium text-gray-900 mb-2">What This Means</h3>
                            <p class="text-sm text-gray-700 mb-4">Your Domain has been suspended and you cannot access your tenant site at this time. If you believe this is an error, please contact support.</p>
                            
                            <h3 class="text-md font-medium text-gray-900 mb-2">Your Current Plan</h3>
                            <p class="text-sm text-gray-700">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($plan) }} Plan
                                </span>
                            </p>
                        </div>
                        
                        <div class="mt-6">
                            <a href="mailto:support@alumnihub.com" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-hover active:bg-primary-dark focus:outline-none focus:border-primary-dark focus:ring ring-primary-light disabled:opacity-25 transition ease-in-out duration-150">
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-suspended-app-layout>
