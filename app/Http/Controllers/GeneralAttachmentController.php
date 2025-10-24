<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GeneralAttachmentController extends Controller
{
    /**
     * Constructor - Apply authentication
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of user's attachments.
     */
    public function index()
    {
        $user = Auth::user();
        $attachments = Attachment::where('uploaded_by', $user->id)
            ->with(['uploader', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('attachments.general.index', compact('attachments', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'document' => 'Document',
            'certificate' => 'Certificate',
            'transcript' => 'Transcript',
            'diploma' => 'Diploma',
            'photo' => 'Photo',
            'presentation' => 'Presentation',
            'portfolio' => 'Portfolio',
            'assignment' => 'Assignment',
            'research' => 'Research Paper',
            'lesson_plan' => 'Lesson Plan',
            'curriculum' => 'Curriculum',
            'other' => 'Other',
        ];

        return view('attachments.general.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttachmentRequest $request)
    {
        // Validation is handled by StoreAttachmentRequest

        $user = Auth::user();
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Determine directory based on user role
        $userType = $user->isInstructor() ? 'instructors' : 'users';
        $directory = "attachments/{$userType}/{$user->id}";
        $filePath = "{$directory}/{$fileName}";

        // Create directory if it doesn't exist
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Store the file
        $file->storeAs($directory, $fileName, 'public');

        $attachment = Attachment::create([
            'attachable_id' => $user->id,
            'attachable_type' => get_class($user),
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'category' => $request->category,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false),
            'is_verified' => $user->isInstructor(), // Auto-verify instructor uploads
            'uploaded_by' => $user->id,
            'verified_at' => $user->isInstructor() ? now() : null,
            'verified_by' => $user->isInstructor() ? $user->id : null,
        ]);

        return redirect()
            ->route('attachments.index')
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        $this->authorizeAttachment($attachment);
        return view('attachments.general.show', compact('attachment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        $this->authorizeAttachment($attachment);

        $categories = [
            'document' => 'Document',
            'certificate' => 'Certificate',
            'transcript' => 'Transcript',
            'diploma' => 'Diploma',
            'photo' => 'Photo',
            'presentation' => 'Presentation',
            'portfolio' => 'Portfolio',
            'assignment' => 'Assignment',
            'research' => 'Research Paper',
            'lesson_plan' => 'Lesson Plan',
            'curriculum' => 'Curriculum',
            'other' => 'Other',
        ];

        return view('attachments.general.edit', compact('attachment', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttachmentRequest $request, Attachment $attachment)
    {
        $this->authorizeAttachment($attachment);

        // Validation is handled by UpdateAttachmentRequest

        $attachment->update([
            'category' => $request->category,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public'),
        ]);

        return redirect()
            ->route('attachments.index')
            ->with('success', 'Attachment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        $this->authorizeAttachment($attachment);

        $fileName = $attachment->original_name;
        
        // Delete the physical file
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()
            ->route('attachments.index')
            ->with('success', "File '{$fileName}' deleted successfully.");
    }

    /**
     * Download the attachment
     */
    public function download(Attachment $attachment)
    {
        // Check if user can access this attachment
        if (!$this->canAccessAttachment($attachment)) {
            abort(403, 'You are not authorized to download this file.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($attachment->file_path)) {
            return redirect()
                ->route('attachments.index')
                ->withErrors(['error' => 'File not found.']);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }

    /**
     * Toggle verification status (instructors and admins only)
     */
    public function toggleVerification(Attachment $attachment)
    {
        $user = Auth::user();
        
        if (!($user->isInstructor() || $user->isAdmin())) {
            abort(403, 'Only instructors and administrators can verify attachments.');
        }

        $attachment->update([
            'is_verified' => !$attachment->is_verified,
            'verified_at' => $attachment->is_verified ? null : now(),
            'verified_by' => $attachment->is_verified ? null : $user->id,
        ]);

        $status = $attachment->is_verified ? 'verified' : 'unverified';
        
        return redirect()
            ->back()
            ->with('success', "Attachment marked as {$status}.");
    }

    /**
     * Public attachments listing
     */
    public function publicIndex()
    {
        $attachments = Attachment::where('is_public', true)
            ->where('is_verified', true)
            ->with(['uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('attachments.general.public', compact('attachments'));
    }

    /**
     * Browse attachments by category
     */
    public function category($category)
    {
        $user = Auth::user();
        
        // Base query - show public verified files to everyone
        $query = Attachment::where('is_public', true)
            ->where('is_verified', true)
            ->where('category', $category);

        // If user is logged in, also show their own files
        if ($user) {
            $query->orWhere(function($q) use ($user, $category) {
                $q->where('uploaded_by', $user->id)
                  ->where('category', $category);
            });
        }

        $attachments = $query->with(['uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('attachments.general.category', compact('attachments', 'category'));
    }

    /**
     * Check if user can access the attachment
     */
    private function canAccessAttachment(Attachment $attachment): bool
    {
        $user = Auth::user();
        
        // Owner can always access
        if ($attachment->uploaded_by === $user->id) {
            return true;
        }
        
        // Public verified files can be accessed by anyone
        if ($attachment->is_public && $attachment->is_verified) {
            return true;
        }
        
        // Instructors and admins can access all files
        if ($user->isInstructor() || $user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Authorize attachment access for modification
     */
    private function authorizeAttachment(Attachment $attachment)
    {
        $user = Auth::user();
        
        // Owner can always modify
        if ($attachment->uploaded_by === $user->id) {
            return;
        }
        
        // Admins can modify any attachment
        if ($user->isAdmin()) {
            return;
        }
        
        abort(403, 'You are not authorized to access this attachment.');
    }
}