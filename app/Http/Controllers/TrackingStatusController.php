<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TrackingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Alumni $alumni)
    {
        $trackingStatuses = $alumni->trackingStatuses()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $currentStatus = $alumni->getCurrentTrackingStatus();
        
        $statusTypes = [
            'registered' => 'Registered',
            'information_requested' => 'Information Requested', 
            'contacted' => 'Contacted',
            'employment_verified' => 'Employment Verified',
            'employment_updated' => 'Employment Updated',
            'lost_contact' => 'Lost Contact',
            'moved' => 'Moved',
            'inactive' => 'Inactive',
            'verified' => 'Verified',
            'other' => 'Other',
        ];

        return view('tracking-statuses.index', compact('alumni', 'trackingStatuses', 'currentStatus', 'statusTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Alumni $alumni)
    {
        $statusTypes = [
            'registered' => 'Registered',
            'information_requested' => 'Information Requested', 
            'contacted' => 'Contacted',
            'employment_verified' => 'Employment Verified',
            'employment_updated' => 'Employment Updated',
            'lost_contact' => 'Lost Contact',
            'moved' => 'Moved',
            'inactive' => 'Inactive',
            'verified' => 'Verified',
            'other' => 'Other',
        ];

        return view('tracking-statuses.create', compact('alumni', 'statusTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Alumni $alumni)
    {
        $request->validate([
            'status_type' => 'required|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'is_current' => 'boolean',
            'follow_up_date' => 'nullable|date|after:today',
        ]);

        // If this is being marked as current, unset the current status of all others
        if ($request->boolean('is_current', false)) {
            $alumni->trackingStatuses()->update(['is_current' => false]);
        }

        $trackingStatus = $alumni->trackingStatuses()->create([
            'status_type' => $request->status_type,
            'notes' => $request->notes,
            'is_current' => $request->boolean('is_current', false),
            'follow_up_date' => $request->follow_up_date,
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('alumni.tracking-statuses.index', $alumni)
            ->with('success', 'Tracking status added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumni $alumni, TrackingStatus $trackingStatus)
    {
        // Ensure the tracking status belongs to the alumni
        if ($trackingStatus->alumni_id !== $alumni->id) {
            abort(404);
        }

        return view('tracking-statuses.show', compact('alumni', 'trackingStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumni, TrackingStatus $trackingStatus)
    {
        // Ensure the tracking status belongs to the alumni
        if ($trackingStatus->alumni_id !== $alumni->id) {
            abort(404);
        }

        $statusTypes = [
            'registered' => 'Registered',
            'information_requested' => 'Information Requested', 
            'contacted' => 'Contacted',
            'employment_verified' => 'Employment Verified',
            'employment_updated' => 'Employment Updated',
            'lost_contact' => 'Lost Contact',
            'moved' => 'Moved',
            'inactive' => 'Inactive',
            'verified' => 'Verified',
            'other' => 'Other',
        ];

        return view('tracking-statuses.edit', compact('alumni', 'trackingStatus', 'statusTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumni, TrackingStatus $trackingStatus)
    {
        // Ensure the tracking status belongs to the alumni
        if ($trackingStatus->alumni_id !== $alumni->id) {
            abort(404);
        }

        $request->validate([
            'status_type' => 'required|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'is_current' => 'boolean',
            'follow_up_date' => 'nullable|date',
        ]);

        // If this is being marked as current, unset the current status of all others
        if ($request->boolean('is_current', false) && !$trackingStatus->is_current) {
            $alumni->trackingStatuses()->where('id', '!=', $trackingStatus->id)->update(['is_current' => false]);
        }

        $trackingStatus->update([
            'status_type' => $request->status_type,
            'notes' => $request->notes,
            'is_current' => $request->boolean('is_current'),
            'follow_up_date' => $request->follow_up_date,
        ]);

        return redirect()
            ->route('alumni.tracking-statuses.index', $alumni)
            ->with('success', 'Tracking status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni, TrackingStatus $trackingStatus)
    {
        // Ensure the tracking status belongs to the alumni
        if ($trackingStatus->alumni_id !== $alumni->id) {
            abort(404);
        }

        $statusType = $trackingStatus->getStatusDisplayName();
        $trackingStatus->delete();

        return redirect()
            ->route('alumni.tracking-statuses.index', $alumni)
            ->with('success', "Tracking status '{$statusType}' deleted successfully.");
    }

    /**
     * Set a tracking status as current
     */
    public function setCurrent(Alumni $alumni, TrackingStatus $trackingStatus)
    {
        // Ensure the tracking status belongs to the alumni
        if ($trackingStatus->alumni_id !== $alumni->id) {
            abort(404);
        }

        // Unset all other current statuses for this alumni
        $alumni->trackingStatuses()->update(['is_current' => false]);
        
        // Set this one as current
        $trackingStatus->update(['is_current' => true]);

        return redirect()
            ->route('alumni.tracking-statuses.index', $alumni)
            ->with('success', 'Current status updated successfully.');
    }

    /**
     * Get alumni by status type
     */
    public function byStatusType(Request $request)
    {
        $request->validate([
            'status_type' => 'required|string',
        ]);

        $alumni = Alumni::whereHas('trackingStatuses', function ($query) use ($request) {
            $query->where('status_type', $request->status_type)
                  ->where('is_current', true);
        })->with(['currentTrackingStatus', 'user'])
        ->paginate(20);

        $statusType = $request->status_type;
        $statusDisplay = TrackingStatus::getStatusDisplayNames()[$statusType] ?? $statusType;

        return view('tracking-statuses.by-status', compact('alumni', 'statusType', 'statusDisplay'));
    }

    /**
     * Get tracking statistics
     */
    public function statistics()
    {
        $stats = [];
        
        $statusTypes = [
            'registered' => 'Registered',
            'information_requested' => 'Information Requested', 
            'contacted' => 'Contacted',
            'employment_verified' => 'Employment Verified',
            'employment_updated' => 'Employment Updated',
            'lost_contact' => 'Lost Contact',
            'moved' => 'Moved',
            'inactive' => 'Inactive',
            'verified' => 'Verified',
            'other' => 'Other',
        ];

        foreach ($statusTypes as $type => $label) {
            $stats[$type] = [
                'label' => $label,
                'count' => Alumni::whereHas('trackingStatuses', function ($query) use ($type) {
                    $query->where('status_type', $type)
                          ->where('is_current', true);
                })->count(),
            ];
        }

        // Get follow-up counts
        $followUpCount = TrackingStatus::where('is_current', true)
            ->whereNotNull('follow_up_date')
            ->where('follow_up_date', '<=', now()->addDays(7))
            ->count();

        $overdueFollowUpCount = TrackingStatus::where('is_current', true)
            ->whereNotNull('follow_up_date')
            ->where('follow_up_date', '<', now())
            ->count();

        return view('tracking-statuses.statistics', compact('stats', 'followUpCount', 'overdueFollowUpCount'));
    }

    /**
     * Get follow-up list
     */
    public function followUps()
    {
        $upcomingFollowUps = TrackingStatus::where('is_current', true)
            ->whereNotNull('follow_up_date')
            ->where('follow_up_date', '>', now())
            ->where('follow_up_date', '<=', now()->addDays(7))
            ->with(['alumni', 'user'])
            ->orderBy('follow_up_date')
            ->get();

        $overdueFollowUps = TrackingStatus::where('is_current', true)
            ->whereNotNull('follow_up_date')
            ->where('follow_up_date', '<', now())
            ->with(['alumni', 'user'])
            ->orderBy('follow_up_date', 'desc')
            ->get();

        return view('tracking-statuses.follow-ups', compact('upcomingFollowUps', 'overdueFollowUps'));
    }
}
