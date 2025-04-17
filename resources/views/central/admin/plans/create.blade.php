<x-central-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            {{ __('Create New Subscription Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.plans.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Plan Name</label>
                                <input type="text" name="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700">Slug (ID)</label>
                                <input type="text" name="slug" id="slug" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('slug') }}" required>
                                <p class="mt-1 text-xs text-gray-500">Unique identifier for the plan. Cannot be changed later.</p>
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Monthly Price -->
                            <div>
                                <label for="monthly_price" class="block text-sm font-medium text-gray-700">Monthly Price ($)</label>
                                <input type="number" name="monthly_price" id="monthly_price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('monthly_price', 0) }}" step="0.01" min="0" required>
                                @error('monthly_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Annual Price -->
                            <div>
                                <label for="annual_price" class="block text-sm font-medium text-gray-700">Annual Price ($)</label>
                                <input type="number" name="annual_price" id="annual_price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('annual_price', 0) }}" step="0.01" min="0" required>
                                @error('annual_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Alumni -->
                            <div>
                                <label for="max_alumni" class="block text-sm font-medium text-gray-700">Max Alumni (0 for unlimited)</label>
                                <input type="number" name="max_alumni" id="max_alumni" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('max_alumni', 0) }}" min="0" required>
                                @error('max_alumni')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Instructors -->
                            <div>
                                <label for="max_instructors" class="block text-sm font-medium text-gray-700">Max Instructors (0 for unlimited)</label>
                                <input type="number" name="max_instructors" id="max_instructors" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('max_instructors', 0) }}" min="0" required>
                                @error('max_instructors')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Support Level -->
                            <div>
                                <label for="support_level" class="block text-sm font-medium text-gray-700">Support Level</label>
                                <select name="support_level" id="support_level" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="community" {{ old('support_level') === 'community' ? 'selected' : '' }}>Community (Forums only)</option>
                                    <option value="email" {{ old('support_level') === 'email' ? 'selected' : '' }}>Email Support</option>
                                    <option value="priority" {{ old('support_level') === 'priority' ? 'selected' : '' }}>Priority Support</option>
                                </select>
                                @error('support_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-span-1 md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 space-y-2">
                            <div class="text-sm font-medium text-gray-700 mb-3">Features</div>
                            
                            <!-- Custom Fields -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="has_custom_fields" name="has_custom_fields" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('has_custom_fields') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_custom_fields" class="font-medium text-gray-700">Custom Profile Fields</label>
                                    <p class="text-gray-500">Allow custom profile fields for alumni and instructors</p>
                                </div>
                            </div>
                            
                            <!-- Advanced Analytics -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="has_advanced_analytics" name="has_advanced_analytics" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('has_advanced_analytics') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_advanced_analytics" class="font-medium text-gray-700">Advanced Analytics</label>
                                    <p class="text-gray-500">Provides detailed analytics and reporting tools</p>
                                </div>
                            </div>
                            
                            <!-- Integrations -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="has_integrations" name="has_integrations" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('has_integrations') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_integrations" class="font-medium text-gray-700">Third-party Integrations</label>
                                    <p class="text-gray-500">Allows integration with other platforms and services</p>
                                </div>
                            </div>
                            
                            <!-- Job Board -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="has_job_board" name="has_job_board" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('has_job_board') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_job_board" class="font-medium text-gray-700">Job Board</label>
                                    <p class="text-gray-500">Includes a job board for alumni opportunities</p>
                                </div>
                            </div>
                            
                            <!-- Custom Branding -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="has_custom_branding" name="has_custom_branding" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('has_custom_branding') ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="has_custom_branding" class="font-medium text-gray-700">Custom Branding</label>
                                    <p class="text-gray-500">Allows custom logos, colors, and branding</p>
                                </div>
                            </div>
                            
                            <!-- Active Status -->
                            <div class="flex items-start mt-4">
                                <div class="flex items-center h-5">
                                    <input id="is_active" name="is_active" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('is_active', true) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-medium text-gray-700">Plan Active</label>
                                    <p class="text-gray-500">Only active plans are visible for subscription</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8">
                            <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-200 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-central-app-layout> 