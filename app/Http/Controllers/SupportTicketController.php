<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketResponse;
use App\Models\TenantSettings;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    /**
     * The notification service instance.
     *
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\NotificationService $notificationService
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth']);
        $this->notificationService = $notificationService;
    }

    /**
     * Determine which layout to use based on user role
     * 
     * @return string
     */
    private function getLayoutBasedOnUserRole()
    {
        $user = Auth::user();
        
        if ($user->isInstructor()) {
            return 'layouts.instructor';
        } elseif ($user->role === 'alumni') {
            return 'layouts.alumni';
        } else {
            return 'layouts.tenant';
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::query();
        
        // If user is not admin/instructor, only show their tickets
        if (!Auth::user()->isAdmin() && !Auth::user()->isInstructor()) {
            $query->where('user_id', Auth::id());
        }
        
        // Search and filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }
        
        // Sort
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $tickets = $query->with('user')->paginate(15);
        $settings = TenantSettings::getSettings();
        
        return view('tenant.support.index', [
            'tickets' => $tickets,
            'filters' => $request->only(['search', 'status', 'priority']),
            'settings' => $settings,
            'layout' => $this->getLayoutBasedOnUserRole(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Admins should primarily respond to tickets, not create them
        // But we'll still allow them to create tickets for testing/demonstration purposes
        $settings = TenantSettings::getSettings();
        
        return view('tenant.support.create', [
            'settings' => $settings,
            'layout' => $this->getLayoutBasedOnUserRole(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->route('support.create')
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open',
        ];
        
        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket-attachments', 'public');
            $data['attachment_path'] = $path;
        }
        
        $ticket = SupportTicket::create($data);
        
        // Send notification to tenant admins
        $this->notificationService->sendNewTicketNotification($ticket);
        
        return redirect()->route('support.show', $ticket->id)
            ->with('success', 'Support ticket created successfully. Our team will respond as soon as possible.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = SupportTicket::with(['user', 'responses.user'])->findOrFail($id);
        
        // Authorization check - only admin/instructor or ticket owner can view
        if (!Auth::user()->isAdmin() && !Auth::user()->isInstructor() && $ticket->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $settings = TenantSettings::getSettings();
        
        return view('tenant.support.show', [
            'ticket' => $ticket,
            'settings' => $settings,
            'layout' => $this->getLayoutBasedOnUserRole(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Authorization check - only admin/instructor or ticket owner can edit
        if (!Auth::user()->isAdmin() && !Auth::user()->isInstructor() && $ticket->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $settings = TenantSettings::getSettings();
        
        return view('tenant.support.edit', [
            'ticket' => $ticket,
            'settings' => $settings,
            'layout' => $this->getLayoutBasedOnUserRole(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Authorization check - only admin/instructor or ticket owner can update
        if (!Auth::user()->isAdmin() && !Auth::user()->isInstructor() && $ticket->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->route('support.edit', $ticket->id)
                ->withErrors($validator)
                ->withInput();
        }

        $ticket->subject = $request->subject;
        $ticket->description = $request->description;
        $ticket->priority = $request->priority;
        
        // Only admin/instructor can change status
        if (Auth::user()->isAdmin() || Auth::user()->isInstructor()) {
            $ticket->status = $request->status;
        }
        
        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($ticket->attachment_path) {
                Storage::disk('public')->delete($ticket->attachment_path);
            }
            
            $path = $request->file('attachment')->store('ticket-attachments', 'public');
            $ticket->attachment_path = $path;
        }
        
        $ticket->save();
        
        return redirect()->route('support.show', $ticket->id)
            ->with('success', 'Support ticket updated successfully.');
    }

    /**
     * Add a response to a ticket.
     */
    public function addResponse(Request $request, string $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Authorization check - only admin/instructor or ticket owner can respond
        if (!Auth::user()->isAdmin() && !Auth::user()->isInstructor() && $ticket->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->route('support.show', $ticket->id)
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_staff_reply' => Auth::user()->isAdmin() || Auth::user()->isInstructor(),
        ];
        
        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket-responses', 'public');
            $data['attachment_path'] = $path;
        }
        
        $response = TicketResponse::create($data);
        
        // Update ticket status if responded by staff
        if (Auth::user()->isAdmin() || Auth::user()->isInstructor()) {
            if ($ticket->status === 'open') {
                $ticket->status = 'in_progress';
                $ticket->save();
            }
        } else {
            // If user responds to a resolved/closed ticket, reopen it
            if (in_array($ticket->status, ['resolved', 'closed'])) {
                $ticket->status = 'open';
                $ticket->save();
            }
        }
        
        // Send notification
        $this->notificationService->sendNewResponseNotification($ticket, $response);
        
        return redirect()->route('support.show', $ticket->id)
            ->with('success', 'Response added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        // Authorization check - only admin or ticket owner can delete
        if (!Auth::user()->isAdmin() && $ticket->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete attachments
        if ($ticket->attachment_path) {
            Storage::disk('public')->delete($ticket->attachment_path);
        }
        
        // Delete responses and their attachments
        foreach ($ticket->responses as $response) {
            if ($response->attachment_path) {
                Storage::disk('public')->delete($response->attachment_path);
            }
            $response->delete();
        }
        
        $ticket->delete();
        
        return redirect()->route('support.index')
            ->with('success', 'Support ticket deleted successfully.');
    }
}
