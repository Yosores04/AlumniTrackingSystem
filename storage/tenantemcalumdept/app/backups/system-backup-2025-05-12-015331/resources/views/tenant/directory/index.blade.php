<style>
    .profile-card {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    
    .profile-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(var(--brand-primary-rgb), 0.1), 0 4px 6px -2px rgba(var(--brand-primary-rgb), 0.05);
    }
    
    .profile-banner {
        height: 80px;
        background-color: rgba(var(--brand-primary-rgb), 0.1);
        position: relative;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid var(--content-bg);
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        background-color: var(--content-bg);
        overflow: hidden;
    }
    
    .profile-info {
        padding: 3rem 1.25rem 1.25rem;
        text-align: center;
    }
    
    .profile-name {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.125rem;
        margin-bottom: 0.25rem;
    }
    
    .profile-batch {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0.75rem;
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background-color: rgba(var(--brand-primary-rgb), 0.1);
        border-radius: 9999px;
    }
    
    .profile-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .profile-tag {
        background-color: rgba(var(--brand-secondary-rgb), 0.1);
        color: var(--text-tertiary);
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }
    
    .profile-connections {
        color: var(--text-tertiary);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .profile-filter {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .profile-filter-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .profile-search {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.12);
        border-radius: 0.375rem;
        background-color: var(--content-bg);
        color: var(--text-primary);
    }
    
    .profile-search:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.15);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Alumni Directory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-semibold mb-6">Alumni Directory</h1>
                    <p>Alumni directory listings will be displayed here.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <!-- Profile cards will be rendered here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 