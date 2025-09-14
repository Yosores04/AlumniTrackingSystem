<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Change Password') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Update Password') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                                </p>
                            </header>

                            @if (session('success'))
                                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                                @csrf

                                <div>
                                    <x-input-label for="current_password" :value="__('Current Password')" />
                                    <x-text-input 
                                        id="current_password" 
                                        name="current_password" 
                                        type="password" 
                                        class="mt-1 block w-full" 
                                        autocomplete="current-password"
                                        required
                                    />
                                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="new_password" :value="__('New Password')" />
                                    <x-text-input 
                                        id="new_password" 
                                        name="new_password" 
                                        type="password" 
                                        class="mt-1 block w-full" 
                                        autocomplete="new-password"
                                        required
                                    />
                                    <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        <p>Password requirements:</p>
                                        <ul class="list-disc list-inside mt-1 space-y-1">
                                            <li>At least 8 characters long</li>
                                            <li>Contains uppercase and lowercase letters</li>
                                            <li>Contains at least one number</li>
                                            <li>Contains at least one special character</li>
                                            <li>Must not be a commonly used password</li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="new_password_confirmation" :value="__('Confirm New Password')" />
                                    <x-text-input 
                                        id="new_password_confirmation" 
                                        name="new_password_confirmation" 
                                        type="password" 
                                        class="mt-1 block w-full" 
                                        autocomplete="new-password"
                                        required
                                    />
                                    <x-input-error :messages="$errors->get('new_password_confirmation')" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Update Password') }}</x-primary-button>

                                    @if (session('status') === 'password-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                        >{{ __('Password updated successfully.') }}</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>