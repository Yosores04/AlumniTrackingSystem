@extends('layouts.tenant')

@section('title', 'System Version Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Version Management</h1>
        <a href="{{ route('system.check-updates') }}" class="bg-secondary hover:bg-primary text-white font-bold py-2 px-4 rounded">
            Check for Updates
        </a>
    </div>

    @if(session('success'))
    <div class="hidden alert-success-message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="hidden alert-error-message">{{ session('error') }}</div>
    @endif

    @if(session('info'))
    <div class="hidden alert-info-message">{{ session('info') }}</div>
    @endif

    @if(session('warning'))
    <div class="hidden alert-warning-message">{{ session('warning') }}</div>
    @endif

    <!-- Current Version -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Current Version</h2>
        
        @if($currentVersion)
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <p><span class="font-semibold">Version:</span> {{ $currentVersion->version }}</p>
                <p><span class="font-semibold">Installed:</span> {{ $currentVersion->formatted_installed_date }}</p>
                @if($currentVersion->github_url)
                <p><span class="font-semibold">GitHub Release:</span> 
                    <a href="{{ $currentVersion->github_url }}" target="_blank" class="text-secondary hover:text-primary">View on GitHub</a>
                </p>
                @endif
            </div>
            <div>
                @if($currentVersion->description)
                <p><span class="font-semibold">Description:</span></p>
                <div class="mt-1 bg-gray-50 p-3 rounded text-sm">
                    {!! nl2br(e($currentVersion->description)) !!}
                </div>
                @endif
            </div>
        </div>
        @else
        <p class="text-gray-600">No version information available. Please check for updates.</p>
        @endif
    </div>

    <!-- Available Updates -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Available Updates</h2>
        
        @if($updates->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Release Tag</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($updates as $version)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $version->version }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $version->release_tag }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ Str::limit($version->description, 100) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('system.update', $version->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-xs">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-600">No updates available. Click "Check for Updates" to see if there are new versions.</p>
        @endif
    </div>

    <!-- Available Rollbacks -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Previous Versions (Rollback)</h2>
        
        @if($rollbacks->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Release Tag</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rollbacks as $version)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $version->version }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $version->release_tag }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ Str::limit($version->description, 100) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('system.rollback', $version->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-xs">
                                    Roll Back
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-600">No previous versions available for rollback.</p>
        @endif
    </div>

    <!-- Warning -->
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm">
                    <strong>Warning:</strong> Always back up your database before updating or rolling back versions. 
                    While the system creates backups automatically, it's recommended to have your own backup as well.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Simple confirmation popup for the update and rollback actions
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const isRollback = form.action.includes('rollback');
            const isUpdate = form.action.includes('update');
            
            if (isRollback || isUpdate) {
                e.preventDefault();
                
                const actionType = isRollback ? 'roll back' : 'update';
                const confirmation = confirm(`Are you sure you want to ${actionType} the system? This action cannot be undone.`);
                
                if (confirmation) {
                    form.submit();
                }
            }
        });
    });
});
</script>

<!-- Include the system alerts JS -->
<script src="{{ asset('js/system-alerts.js') }}"></script>
@endsection 