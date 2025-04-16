<style>
    .announcement-card {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .announcement-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(var(--brand-primary-rgb), 0.1), 0 4px 6px -2px rgba(var(--brand-primary-rgb), 0.05);
    }
    
    .announcement-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .announcement-category {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        background-color: rgba(var(--brand-secondary-rgb), 0.1);
        color: var(--brand-secondary);
    }
    
    .announcement-date {
        font-size: 0.875rem;
        color: var(--text-tertiary);
    }
    
    .announcement-content {
        padding: 1.25rem;
    }
    
    .announcement-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .announcement-description {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }
    
    .announcement-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1.25rem;
        background-color: rgba(var(--brand-secondary-rgb), 0.03);
        border-top: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
    }
    
    .announcement-author {
        display: flex;
        align-items: center;
    }
    
    .announcement-author-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        overflow: hidden;
        margin-right: 0.75rem;
        border: 2px solid rgba(var(--brand-primary-rgb), 0.1);
    }
    
    .announcement-author-info {
        line-height: 1.3;
    }
    
    .announcement-author-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9375rem;
    }
    
    .announcement-author-role {
        color: var(--text-tertiary);
        font-size: 0.8125rem;
    }
    
    .announcement-filter {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .announcement-filter-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .announcement-search {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.12);
        border-radius: 0.375rem;
        background-color: var(--content-bg);
        color: var(--text-primary);
    }
    
    .announcement-search:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.15);
    }
</style> 