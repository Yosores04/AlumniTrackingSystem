<?php

namespace App\Http\Controllers;

use App\Models\DomainRequest;
use App\Models\Tenant;
use App\Notifications\DomainRequestReceived;
use App\Notifications\DomainRequestApproved;
use App\Notifications\DomainRequestRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DomainRequestController extends Controller
{
    /**
     * Show the domain request form for public users
     */
    public function showRequestForm()
    {
        return view('domain-requests.create');
    }

    /**
     * Store a new domain request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'domain_prefix' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z0-9\-]+$/i',
                Rule::unique('domains', 'domain')->where(function ($query) use ($request) {
                    return $query->where('domain', $request->domain_prefix . '.localhost:8000');
                }),
                Rule::unique('domain_requests', 'domain_prefix')->where(function ($query) use ($request) {
                    return $query->where('status', 'pending');
                }),
            ],
        ], [
            'domain_prefix.regex' => 'The domain prefix may only contain letters, numbers, and hyphens.',
            'domain_prefix.unique' => 'This domain is already taken or there is already a pending request for it.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('request-domain')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create the domain request
            $domainRequest = DomainRequest::create([
                'admin_name' => $request->admin_name,
                'admin_email' => $request->admin_email,
                'domain_prefix' => $request->domain_prefix,
                'status' => 'pending',
            ]);

            // Send confirmation email with better error handling
            try {
                Log::info('Attempting to send domain request notification', [
                    'email' => $request->admin_email,
                    'domain_prefix' => $request->domain_prefix
                ]);
                
                Notification::route('mail', $request->admin_email)
                    ->notify(new DomainRequestReceived($domainRequest));
                    
                Log::info('Domain request notification sent successfully');
            } catch (\Exception $emailEx) {
                Log::error('Failed to send domain request email notification', [
                    'error' => $emailEx->getMessage(),
                    'trace' => $emailEx->getTraceAsString()
                ]);
                // Continue execution even if email fails
            }

            return redirect()->route('request-domain')
                ->with('success', 'Your domain request has been submitted successfully. We will notify you once it has been reviewed.');
                
        } catch (\Exception $e) {
            Log::error('Failed to submit domain request', ['error' => $e->getMessage()]);
            return redirect()->route('request-domain')
                ->with('error', 'Error submitting domain request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show domain requests for admins
     */
    public function index()
    {
        $pendingRequests = DomainRequest::where('status', 'pending')->latest()->get();
        $processedRequests = DomainRequest::where('status', '!=', 'pending')->latest()->paginate(10);
        
        return view('domain-requests.index', compact('pendingRequests', 'processedRequests'));
    }

    /**
     * Approve a domain request
     */
    public function approve($id)
    {
        $domainRequest = DomainRequest::findOrFail($id);
        
        if ($domainRequest->status !== 'pending') {
            return redirect()->route('domain-requests.index')
                ->with('error', 'This request has already been processed.');
        }

        try {
            // Create domain with .localhost
            $domain = $domainRequest->domain_prefix . '.localhost';
            
            // Check if domain already exists
            if (Tenant::find($domainRequest->domain_prefix)) {
                throw new \Exception('This domain is already taken.');
            }

            // Create tenant
            $tenant = Tenant::create([
                'id' => $domainRequest->domain_prefix,
                'data' => ['status' => 'active']
            ]);
            $tenant->domains()->create(['domain' => $domain]);

            // Run migrations and create admin user
            tenancy()->initialize($tenant);
            
            // Create admin user for this new tenant with a random password
            $password = \Illuminate\Support\Str::random(10);
            \App\Models\User::create([
                'name' => $domainRequest->admin_name,
                'email' => $domainRequest->admin_email,
                'password' => bcrypt($password),
                'role' => \App\Models\User::ROLE_TENANT_ADMIN,
            ]);

            // Return to central context
            tenancy()->end();

            // Update domain request status
            $domainRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::user()->name ?? 'System',
            ]);

            // Prepare domain information for display and email (include port for URLs)
            $domainWithPort = $domain . ':8000';
            
            // Create credentials array for email with the password
            $credentials = [
                'domain' => $domain,
                'name' => $domainRequest->admin_name,
                'email' => $domainRequest->admin_email,
                'password' => $password,
                'login_url' => 'http://' . $domainWithPort,
            ];

            // Send approval email with better error handling
            try {
                Log::info('Attempting to send domain approval notification', [
                    'email' => $domainRequest->admin_email,
                    'domain' => $domain
                ]);
                
                Notification::route('mail', $domainRequest->admin_email)
                    ->notify(new DomainRequestApproved($credentials));
                    
                Log::info('Domain approval notification sent successfully');
            } catch (\Exception $emailEx) {
                Log::error('Failed to send domain approval email notification', [
                    'error' => $emailEx->getMessage(),
                    'trace' => $emailEx->getTraceAsString()
                ]);
                // Continue execution even if email fails
            }

            return redirect()->route('domain-requests.index')
                ->with('success', "Domain request for '{$domainRequest->domain_prefix}' has been approved. The domain is now accessible at http://{$domainWithPort}");
                
        } catch (\Exception $e) {
            Log::error('Failed to approve domain request', ['error' => $e->getMessage()]);
            return redirect()->route('domain-requests.index')
                ->with('error', 'Error approving domain request: ' . $e->getMessage());
        }
    }

    /**
     * Reject a domain request
     */
    public function reject(Request $request, $id)
    {
        $domainRequest = DomainRequest::findOrFail($id);
        
        if ($domainRequest->status !== 'pending') {
            return redirect()->route('domain-requests.index')
                ->with('error', 'This request has already been processed.');
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('domain-requests.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update domain request status
            $domainRequest->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => Auth::user()->name ?? 'System',
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Send rejection email with better error handling
            try {
                Log::info('Attempting to send domain rejection notification', [
                    'email' => $domainRequest->admin_email,
                    'domain_prefix' => $domainRequest->domain_prefix
                ]);
                
                Notification::route('mail', $domainRequest->admin_email)
                    ->notify(new DomainRequestRejected($domainRequest));
                    
                Log::info('Domain rejection notification sent successfully');
            } catch (\Exception $emailEx) {
                Log::error('Failed to send domain rejection email notification', [
                    'error' => $emailEx->getMessage(),
                    'trace' => $emailEx->getTraceAsString()
                ]);
                // Continue execution even if email fails
            }

            return redirect()->route('domain-requests.index')
                ->with('success', "Domain request for '{$domainRequest->domain_prefix}' has been rejected.");
                
        } catch (\Exception $e) {
            Log::error('Failed to reject domain request', ['error' => $e->getMessage()]);
            return redirect()->route('domain-requests.index')
                ->with('error', 'Error rejecting domain request: ' . $e->getMessage());
        }
    }
}
