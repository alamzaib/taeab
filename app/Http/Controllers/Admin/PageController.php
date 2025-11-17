<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        }

        $pages = $query->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $page = new Page();
        return view('admin.pages.create', compact('page'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePage($request);

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $this->validatePage($request, $page);

        if (empty($data['slug']) || $page->slug !== $data['slug']) {
            $data['slug'] = $this->generateUniqueSlug($data['slug'] ?: $data['title'], $page->id);
        }

        if ($data['status'] === 'published' && !$page->published_at) {
            $data['published_at'] = now();
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    protected function validatePage(Request $request, ?Page $page = null): array
    {
        $pageId = $page?->id;

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', 'unique:pages,slug,' . $pageId],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        if (empty($slug)) {
            $slug = Str::random(8);
        }

        $originalSlug = $slug;
        $counter = 1;

        while (Page::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}

