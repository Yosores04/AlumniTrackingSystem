<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-2">{{ __('Profile Information') }}</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</h3>
                                    <p class="mt-1">{{ $user->name }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</h3>
                                    <p class="mt-1">{{ $user->email }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email Verification') }}</h3>
                                    <p class="mt-1">
                                        @if ($user->email_verified_at)
                                            <span class="text-green-500">{{ __('Verified') }}</span>
                                        @else
                                            <span class="text-red-500">{{ __('Not verified') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Account Created') }}</h3>
                                    <p class="mt-1">{{ $user->created_at->format('F j, Y') }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</h3>
                                    <p class="mt-1">{{ $user->updated_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('tenant.profile.edit') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                {{ __('Edit Profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 