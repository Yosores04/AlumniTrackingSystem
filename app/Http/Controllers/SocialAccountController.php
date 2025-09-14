<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\SocialAccount;
use Illuminate\Http\Request;

class SocialAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Alumni $alumni)
    {
        $socialAccounts = $alumni->socialAccounts()
            ->orderBy('platform')
            ->paginate(10);

        return view('social-accounts.index', compact('alumni', 'socialAccounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Alumni $alumni)
    {
        $platforms = [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter/X',
            'instagram' => 'Instagram',
            'github' => 'GitHub',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'website' => 'Personal Website',
        ];

        return view('social-accounts.create', compact('alumni', 'platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Alumni $alumni)
    {
        $request->validate([
            'platform' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'profile_url' => 'nullable|url|max:500',
            'is_public' => 'boolean',
            'metadata' => 'nullable|json',
        ]);

        // Check for duplicate platform
        $existing = $alumni->socialAccounts()->where('platform', $request->platform)->first();
        if ($existing) {
            return back()->withErrors(['platform' => 'A social account for this platform already exists.'])->withInput();
        }

        $socialAccount = $alumni->socialAccounts()->create([
            'platform' => $request->platform,
            'username' => $request->username,
            'profile_url' => $request->profile_url,
            'is_public' => $request->boolean('is_public', true),
            'is_verified' => false,
            'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
            'last_updated' => now(),
        ]);

        return redirect()
            ->route('alumni.social-accounts.index', $alumni)
            ->with('success', 'Social account added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumni $alumni, SocialAccount $socialAccount)
    {
        // Ensure the social account belongs to the alumni
        if ($socialAccount->alumni_id !== $alumni->id) {
            abort(404);
        }

        return view('social-accounts.show', compact('alumni', 'socialAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumni, SocialAccount $socialAccount)
    {
        // Ensure the social account belongs to the alumni
        if ($socialAccount->alumni_id !== $alumni->id) {
            abort(404);
        }

        $platforms = [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter/X',
            'instagram' => 'Instagram',
            'github' => 'GitHub',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'website' => 'Personal Website',
        ];

        return view('social-accounts.edit', compact('alumni', 'socialAccount', 'platforms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumni, SocialAccount $socialAccount)
    {
        // Ensure the social account belongs to the alumni
        if ($socialAccount->alumni_id !== $alumni->id) {
            abort(404);
        }

        $request->validate([
            'platform' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'profile_url' => 'nullable|url|max:500',
            'is_public' => 'boolean',
            'metadata' => 'nullable|json',
        ]);

        // Check for duplicate platform (excluding current record)
        $existing = $alumni->socialAccounts()
            ->where('platform', $request->platform)
            ->where('id', '!=', $socialAccount->id)
            ->first();
            
        if ($existing) {
            return back()->withErrors(['platform' => 'A social account for this platform already exists.'])->withInput();
        }

        $socialAccount->update([
            'platform' => $request->platform,
            'username' => $request->username,
            'profile_url' => $request->profile_url,
            'is_public' => $request->boolean('is_public'),
            'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
            'last_updated' => now(),
        ]);

        return redirect()
            ->route('alumni.social-accounts.index', $alumni)
            ->with('success', 'Social account updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni, SocialAccount $socialAccount)
    {
        // Ensure the social account belongs to the alumni
        if ($socialAccount->alumni_id !== $alumni->id) {
            abort(404);
        }

        $platform = $socialAccount->platform_display_name;
        $socialAccount->delete();

        return redirect()
            ->route('alumni.social-accounts.index', $alumni)
            ->with('success', "{$platform} account deleted successfully.");
    }

    /**
     * Toggle verification status
     */
    public function toggleVerification(Alumni $alumni, SocialAccount $socialAccount)
    {
        // Ensure the social account belongs to the alumni
        if ($socialAccount->alumni_id !== $alumni->id) {
            abort(404);
        }

        $socialAccount->update([
            'is_verified' => !$socialAccount->is_verified,
            'last_updated' => now(),
        ]);

        $status = $socialAccount->is_verified ? 'verified' : 'unverified';
        
        return redirect()
            ->route('alumni.social-accounts.index', $alumni)
            ->with('success', "Social account marked as {$status}.");
    }

    /**
     * Toggle public/private status
     */
    public function toggleVisibility(Alumni $alumni, SocialAccount $socialAccount)
    {
        // Ensure the social account belongs to the alumni
        if ($socialAccount->alumni_id !== $alumni->id) {
            abort(404);
        }

        $socialAccount->update([
            'is_public' => !$socialAccount->is_public,
            'last_updated' => now(),
        ]);

        $visibility = $socialAccount->is_public ? 'public' : 'private';
        
        return redirect()
            ->route('alumni.social-accounts.index', $alumni)
            ->with('success', "Social account visibility set to {$visibility}.");
    }
}
