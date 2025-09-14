<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Alumni $alumni)
    {
        $attachments = $alumni->attachments()
            ->with(['uploader', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('attachments.index', compact('alumni', 'attachments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Alumni $alumni)
    {
        $categories = [
            'resume' => 'Resume/CV',
            'certificate' => 'Certificate',
            'transcript' => 'Transcript',
            'diploma' => 'Diploma',
            'photo' => 'Photo',
            'portfolio' => 'Portfolio',
            'recommendation' => 'Recommendation Letter',
            'other' => 'Other',
        ];

        return view('attachments.create', compact('alumni', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Alumni $alumni)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $filePath = "attachments/{$alumni->id}/" . $fileName;

        // Store the file
        $file->storeAs('attachments/' . $alumni->id, $fileName, 'public');

        $attachment = $alumni->attachments()->create([
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'category' => $request->category,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false),
            'is_verified' => false,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()
            ->route('alumni.attachments.index', $alumni)
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumni $alumni, Attachment $attachment)
    {
        // Ensure the attachment belongs to the alumni
        if ($attachment->attachable_id !== $alumni->id || $attachment->attachable_type !== Alumni::class) {
            abort(404);
        }

        return view('attachments.show', compact('alumni', 'attachment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumni, Attachment $attachment)
    {
        // Ensure the attachment belongs to the alumni
        if ($attachment->attachable_id !== $alumni->id || $attachment->attachable_type !== Alumni::class) {
            abort(404);
        }

        $categories = [
            'resume' => 'Resume/CV',
            'certificate' => 'Certificate',
            'transcript' => 'Transcript',
            'diploma' => 'Diploma',
            'photo' => 'Photo',
            'portfolio' => 'Portfolio',
            'recommendation' => 'Recommendation Letter',
            'other' => 'Other',
        ];

        return view('attachments.edit', compact('alumni', 'attachment', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumni, Attachment $attachment)
    {
        // Ensure the attachment belongs to the alumni
        if ($attachment->attachable_id !== $alumni->id || $attachment->attachable_type !== Alumni::class) {
            abort(404);
        }

        $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $attachment->update([
            'category' => $request->category,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public'),
        ]);

        return redirect()
            ->route('alumni.attachments.index', $alumni)
            ->with('success', 'Attachment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni, Attachment $attachment)
    {
        // Ensure the attachment belongs to the alumni
        if ($attachment->attachable_id !== $alumni->id || $attachment->attachable_type !== Alumni::class) {
            abort(404);
        }

        $fileName = $attachment->original_name;
        
        // Delete the physical file
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()
            ->route('alumni.attachments.index', $alumni)
            ->with('success', "File '{$fileName}' deleted successfully.");
    }

    /**
     * Download the attachment
     */
    public function download(Alumni $alumni, Attachment $attachment)
    {
        // Ensure the attachment belongs to the alumni
        if ($attachment->attachable_id !== $alumni->id || $attachment->attachable_type !== Alumni::class) {
            abort(404);
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()
                ->route('alumni.attachments.index', $alumni)
                ->withErrors(['error' => 'File not found.']);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }

    /**
     * Toggle verification status
     */
    public function toggleVerification(Alumni $alumni, Attachment $attachment)
    {
        // Ensure the attachment belongs to the alumni
        if ($attachment->attachable_id !== $alumni->id || $attachment->attachable_type !== Alumni::class) {
            abort(404);
        }

        $attachment->update([
            'is_verified' => !$attachment->is_verified,
            'verified_at' => $attachment->is_verified ? null : now(),
            'verified_by' => $attachment->is_verified ? null : Auth::id(),
        ]);

        $status = $attachment->is_verified ? 'verified' : 'unverified';
        
        return redirect()
            ->route('alumni.attachments.index', $alumni)
            ->with('success', "Attachment marked as {$status}.");
    }

    /**
     * Toggle public/private status
     */
    public function toggleVisibility(Alumni $alumni, Attachment $attachment)
    {
        // Ensure the attachment belongs to the alumni
        if ($attachment->attachable_id !== $alumni->id || $attachment->attachable_type !== Alumni::class) {
            abort(404);
        }

        $attachment->update([
            'is_public' => !$attachment->is_public,
        ]);

        $visibility = $attachment->is_public ? 'public' : 'private';
        
        return redirect()
            ->route('alumni.attachments.index', $alumni)
            ->with('success', "Attachment visibility set to {$visibility}.");
    }
}
