<style>
    /* Using our global color system with RGB variables for transparency effects */
    :root {
        --brand-primary-rgb: 42, 114, 178; /* Sample value - will be derived from primary color */
        --brand-secondary-rgb: 68, 68, 68; /* Sample value - will be derived from secondary color */
        --accent-color-rgb: 255, 123, 0; /* Sample value - will be derived from accent color */
    }
    
    .job-card {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .job-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(var(--brand-primary-rgb), 0.1), 0 4px 6px -2px rgba(var(--brand-primary-rgb), 0.05);
    }
    
    .job-company {
        color: var(--brand-primary);
        font-weight: 600;
    }
    
    .job-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .job-location {
        display: inline-block;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        background-color: rgba(var(--brand-primary-rgb), 0.15);
        color: var(--brand-primary);
    }
    
    .job-type {
        display: inline-block;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        background-color: rgba(var(--brand-secondary-rgb), 0.15);
        color: var(--brand-secondary);
    }
    
    .job-salary {
        display: inline-block;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        background-color: rgba(var(--accent-color-rgb), 0.15);
        color: var(--accent-color);
    }
    
    .job-date {
        font-size: 0.875rem;
        color: var(--text-tertiary);
    }
    
    .job-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-top: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .job-info {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: var(--text-tertiary);
        font-size: 0.875rem;
    }
    
    .job-info i {
        margin-right: 0.5rem;
        color: var(--brand-primary);
    }
    
    .job-link {
        color: var(--brand-primary);
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .job-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
    
    .filter-container {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .job-search {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.12);
        border-radius: 0.375rem;
        background-color: var(--content-bg);
        color: var(--text-primary);
    }
    
    .job-search:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.15);
    }
    
    .filter-btn {
        display: inline-block;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        padding: 0.4rem 0.8rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.12);
        background-color: var(--content-bg);
        color: var(--text-secondary);
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .filter-btn:hover {
        border-color: var(--brand-primary);
        color: var(--brand-primary);
    }
    
    .filter-btn.active {
        background-color: var(--brand-primary);
        color: white;
        border-color: var(--brand-primary);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Job Opportunities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                    <h1 class="text-2xl font-semibold mb-4 md:mb-0">Job Listings</h1>
                    <a href="#" class="btn btn-primary inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Post a Job
                    </a>
                </div>
                
                <!-- Filter Section -->
                <div class="filter-container mb-6">
                    <h3 class="filter-title">Filter Jobs</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="job-search" class="block text-sm font-medium mb-1">Search</label>
                            <input type="text" id="job-search" placeholder="Search jobs..." class="job-search">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Job Type</label>
                            <div class="flex flex-wrap">
                                <button class="filter-btn active">All</button>
                                <button class="filter-btn">Full-time</button>
                                <button class="filter-btn">Part-time</button>
                                <button class="filter-btn">Contract</button>
                                <button class="filter-btn">Internship</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Industry</label>
                            <div class="flex flex-wrap">
                                <button class="filter-btn active">All</button>
                                <button class="filter-btn">Technology</button>
                                <button class="filter-btn">Finance</button>
                                <button class="filter-btn">Education</button>
                                <button class="filter-btn">Healthcare</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Job Listings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Example job cards - in a real implementation these would be generated from data -->
                    <div class="job-card p-6">
                        <div class="mb-1">
                            <span class="job-company">Acme Inc.</span>
                        </div>
                        <h3 class="job-title">Senior Software Engineer</h3>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="job-location">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                San Francisco, CA
                            </span>
                            <span class="job-type">
                                <i class="fas fa-briefcase mr-1"></i>
                                Full-time
                            </span>
                            <span class="job-salary">
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                $120k - $150k
                            </span>
                        </div>
                        <p class="job-description">
                            We are looking for a Senior Software Engineer to join our team. In this role, you will design, develop, and maintain software applications using modern technologies...
                        </p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="job-date">Posted 5 days ago</span>
                            <a href="#" class="job-link">View Details →</a>
                        </div>
                    </div>
                    
                    <div class="job-card p-6">
                        <div class="mb-1">
                            <span class="job-company">TechStart</span>
                        </div>
                        <h3 class="job-title">UX/UI Designer</h3>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="job-location">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                Remote
                            </span>
                            <span class="job-type">
                                <i class="fas fa-briefcase mr-1"></i>
                                Contract
                            </span>
                            <span class="job-salary">
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                $80k - $100k
                            </span>
                        </div>
                        <p class="job-description">
                            We're seeking a talented UX/UI Designer to create exceptional user experiences. You'll work closely with product managers and engineers to design beautiful, intuitive interfaces...
                        </p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="job-date">Posted 2 days ago</span>
                            <a href="#" class="job-link">View Details →</a>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    <nav class="inline-flex rounded-md shadow">
                        <a href="#" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Previous
                        </a>
                        <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-brand-primary">
                            1
                        </a>
                        <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            2
                        </a>
                        <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            3
                        </a>
                        <a href="#" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            Next
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 