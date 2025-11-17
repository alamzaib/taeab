<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $seeker = $request->user('seeker');
        $documents = $seeker->documents()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('seeker.documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $seeker = $request->user('seeker');

        $validated = $request->validate([
            'type' => 'required|in:resume,cover_letter',
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'set_default' => 'sometimes|boolean',
        ]);

        $file = $request->file('file');
        $path = $file->store('job-documents', 'public');

        $shouldBeDefault = $request->boolean('set_default')
            || !$seeker->documents()
                ->where('type', $validated['type'])
                ->where('is_default', true)
                ->exists();

        if ($shouldBeDefault) {
            $seeker->documents()
                ->where('type', $validated['type'])
                ->update(['is_default' => false]);
        }

        $seeker->documents()->create([
            'type' => $validated['type'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'is_default' => $shouldBeDefault,
        ]);

        return back()->with('success', ucfirst(str_replace('_', ' ', $validated['type'])) . ' uploaded successfully.');
    }

    public function destroy(Request $request, JobDocument $document)
    {
        $this->authorizeDocument($request, $document);

        if ($document->is_default) {
            return back()->with('error', 'Cannot delete a default document. Please set another default first.');
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    public function makeDefault(Request $request, JobDocument $document)
    {
        $this->authorizeDocument($request, $document);

        $request->user('seeker')
            ->documents()
            ->where('type', $document->type)
            ->update(['is_default' => false]);

        $document->update(['is_default' => true]);

        return back()->with('success', 'Default document updated.');
    }

    protected function authorizeDocument(Request $request, JobDocument $document): void
    {
        if ($document->seeker_id !== $request->user('seeker')->id) {
            abort(403);
        }
    }
}
