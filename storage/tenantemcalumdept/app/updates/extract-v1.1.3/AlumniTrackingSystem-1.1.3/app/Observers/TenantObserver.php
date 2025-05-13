<?php

namespace App\Observers;

use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TenantObserver
{
    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        // Get original attributes to compare what changed
        $original = $tenant->getOriginal();
        
        // Check if status was changed (using the dedicated status column)
        if (isset($original['status']) && $original['status'] !== $tenant->status) {
            Log::info('Tenant status changed', [
                'tenant_id' => $tenant->id,
                'old_status' => $original['status'],
                'new_status' => $tenant->status
            ]);
            
            // Handle status change
            if ($tenant->status === 'suspended') {
                $this->handleTenantSuspended($tenant);
            } elseif ($tenant->status === 'active' && $original['status'] === 'suspended') {
                $this->handleTenantActivated($tenant);
            }
        }
        
        // Check if subscription was changed
        if (isset($tenant->subscription) && 
            (!isset($original['subscription']) || $original['subscription'] !== $tenant->subscription)) {
            
            Log::info('Tenant subscription changed', [
                'tenant_id' => $tenant->id,
                'old_plan' => $original['subscription']['plan'] ?? 'none',
                'new_plan' => $tenant->subscription['plan'] ?? 'none'
            ]);
            
            $this->handleSubscriptionChange($tenant, $original['subscription'] ?? null);
        }
    }
    
    /**
     * Handle a tenant being suspended
     */
    private function handleTenantSuspended(Tenant $tenant): void
    {
        // Disable scheduled commands for this tenant
        Cache::put("tenant:{$tenant->id}:suspended", true);
        
        // Revoke tokens or sessions if needed
        // Here you could implement token revocation if using API tokens
        
        Log::info('Tenant suspended actions completed', ['tenant_id' => $tenant->id]);
    }
    
    /**
     * Handle a tenant being activated after suspension
     */
    private function handleTenantActivated(Tenant $tenant): void
    {
        // Re-enable scheduled commands
        Cache::forget("tenant:{$tenant->id}:suspended");
        
        Log::info('Tenant activated actions completed', ['tenant_id' => $tenant->id]);
    }
    
    /**
     * Handle subscription changes
     */
    private function handleSubscriptionChange(Tenant $tenant, ?array $oldSubscription): void
    {
        // Check if plan was upgraded or downgraded
        $oldPlan = $oldSubscription['plan'] ?? 'free';
        $newPlan = $tenant->subscription['plan'] ?? 'free';
        
        // Execute plan-specific adjustments
        // This could include updating limits, features, etc.
        switch ($newPlan) {
            case 'premium':
                // Enable premium features
                $tenant->data['features'] = array_merge($tenant->data['features'] ?? [], [
                    'max_users' => 100,
                    'storage' => '50GB',
                    'advanced_reports' => true
                ]);
                break;
                
            case 'basic':
                // Set basic features
                $tenant->data['features'] = array_merge($tenant->data['features'] ?? [], [
                    'max_users' => 20,
                    'storage' => '10GB',
                    'advanced_reports' => false
                ]);
                break;
                
            case 'free':
            default:
                // Set free features
                $tenant->data['features'] = array_merge($tenant->data['features'] ?? [], [
                    'max_users' => 5,
                    'storage' => '1GB',
                    'advanced_reports' => false
                ]);
                break;
        }
        
        // Save the updated features without triggering observer again
        $tenant->saveQuietly();
        
        Log::info('Tenant plan updated', [
            'tenant_id' => $tenant->id,
            'old_plan' => $oldPlan,
            'new_plan' => $newPlan,
            'features' => $tenant->data['features'] ?? []
        ]);
    }
}
