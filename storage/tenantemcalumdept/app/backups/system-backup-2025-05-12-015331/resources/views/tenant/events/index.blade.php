<style>
    /* Using our global color system with RGB variables for transparency effects */
    .event-card {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .event-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(var(--brand-primary-rgb), 0.1), 0 4px 6px -2px rgba(var(--brand-primary-rgb), 0.05);
    }
    
    .event-image {
        height: 150px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .event-date {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: rgba(var(--brand-primary-rgb), 0.9);
        color: white;
        font-weight: 600;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        text-align: center;
        line-height: 1.2;
        min-width: 60px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .event-date-day {
        font-size: 1.25rem;
    }
    
    .event-date-month {
        font-size: 0.75rem;
        text-transform: uppercase;
    }
    
    .event-content {
        padding: 1.25rem;
    }
    
    .event-category {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.25rem 0.5rem;
        margin-bottom: 0.75rem;
        border-radius: 9999px;
        background-color: rgba(var(--brand-secondary-rgb), 0.1);
        color: var(--brand-secondary);
    }
    
    .event-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .event-info {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: var(--text-tertiary);
        font-size: 0.875rem;
    }
    
    .event-info i {
        margin-right: 0.5rem;
        color: var(--brand-primary);
    }
    
    .event-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-top: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .event-filter {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .event-filter-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .event-search {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.12);
        border-radius: 0.375rem;
        background-color: var(--content-bg);
        color: var(--text-primary);
    }
    
    .event-search:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.15);
    }
    
    .event-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: rgba(var(--accent-color-rgb), 0.15);
        color: var(--accent-color);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
    }
    
    .event-location {
        display: flex;
        align-items: center;
    }
    
    .event-link {
        color: var(--brand-primary);
        transition: color 0.2s;
    }
    
    .event-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
    
    .category-filter {
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.1);
        transition: background-color 0.2s;
    }
    
    .category-filter.active {
        background-color: var(--brand-primary);
        color: white;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-semibold mb-6">Upcoming Events</h1>
                    <p>Event listings will be displayed here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 