<style>
    .opportunity-card {
        background-color: var(--content-bg);
        border-radius: 0.5rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        box-shadow: 0 4px 6px -1px rgba(var(--brand-secondary-rgb), 0.05), 0 2px 4px -1px rgba(var(--brand-secondary-rgb), 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
        margin-bottom: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .opportunity-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(var(--brand-primary-rgb), 0.1), 0 4px 6px -2px rgba(var(--brand-primary-rgb), 0.05);
    }
    
    .opportunity-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .opportunity-company {
        display: flex;
        align-items: center;
    }
    
    .opportunity-company-logo {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        overflow: hidden;
        margin-right: 0.75rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .opportunity-company-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .opportunity-company-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }
    
    .opportunity-badge {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        background-color: rgba(var(--brand-primary-rgb), 0.1);
        color: var(--brand-primary);
    }
    
    .opportunity-content {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .opportunity-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .opportunity-description {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
        flex-grow: 1;
    }
    
    .opportunity-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .opportunity-meta-item {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: var(--text-tertiary);
    }
    
    .opportunity-meta-item i {
        margin-right: 0.375rem;
        font-size: 0.875rem;
        color: rgba(var(--brand-secondary-rgb), 0.7);
    }
    
    .opportunity-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1.25rem;
        background-color: rgba(var(--brand-secondary-rgb), 0.03);
        border-top: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
    }
    
    .opportunity-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .opportunity-date {
        font-size: 0.875rem;
        color: var(--text-tertiary);
    }
    
    .opportunity-filter {
        background-color: var(--content-bg);
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.08);
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .opportunity-filter-title {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .opportunity-search {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(var(--brand-secondary-rgb), 0.12);
        border-radius: 0.375rem;
        background-color: var(--content-bg);
        color: var(--text-primary);
    }
    
    .opportunity-search:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(var(--brand-primary-rgb), 0.15);
    }
</style> 