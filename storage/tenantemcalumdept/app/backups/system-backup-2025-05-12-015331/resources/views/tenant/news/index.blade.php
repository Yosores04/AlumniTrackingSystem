<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('News') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-semibold mb-6">Latest News</h1>
                    <p>News articles will be displayed here.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .news-card {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .news-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(var(--brand-primary-rgb), 0.1), 0 4px 6px -2px rgba(var(--brand-primary-rgb), 0.05);
    }
    
    .news-image {
        height: 200px;
        overflow: hidden;
    }
    
    .news-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: rgba(var(--brand-primary-rgb), 0.15);
        color: var(--brand-primary);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
    }
    
    .news-details {
        padding: 1.25rem;
    }
    
    .news-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }
    
    .news-meta {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }
    
    .news-date {
        display: flex;
        align-items: center;
        margin-right: 1rem;
    }
    
    .news-author {
        display: flex;
        align-items: center;
    }
    
    .news-description {
        color: var(--text-tertiary);
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }
    
    .news-filter {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .news-filter-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .news-search {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.12);
        border-radius: 0.375rem;
        background-color: var(--content-bg);
        color: var(--text-primary);
    }
    
    .news-search:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.15);
    }
</style> 