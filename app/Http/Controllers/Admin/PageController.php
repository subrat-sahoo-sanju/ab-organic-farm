<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    /** List pages with tab-wise filtering (all / active / inactive / search). */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'all');
        $search = trim($request->query('q', ''));

        $query = Page::query();

        if ($tab === 'active') {
            $query->where('is_active', true);
        } elseif ($tab === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $pages = $query->orderBy('sort_order', 'asc')->orderBy('updated_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.pages.index', [
            'pages' => $pages,
            'tab' => $tab,
            'search' => $search,
            'allCount' => Page::count(),
            'activeCount' => Page::where('is_active', true)->count(),
            'inactiveCount' => Page::where('is_active', false)->count(),
            'icons' => $this->iconOptions(),
        ]);
    }

    /** Return the create form (or a blank page for the modal). */
    public function create(Request $request): View
    {
        return view('admin.pages.form', [
            'page' => null,
            'icons' => $this->iconOptions(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->save($request, new Page());
    }

    public function edit(Request $request, Page $page): View
    {
        return view('admin.pages.form', [
            'page' => $page,
            'icons' => $this->iconOptions(),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        return $this->save($request, $page, true);
    }

    protected function save(Request $request, Page $page, bool $updating = false): RedirectResponse
    {
        $data = $this->validated($request, $page);

        // Assemble sections & faqs from the flexible repeater form inputs.
        $data['sections'] = $this->collectSections($request);
        $data['faqs'] = $this->collectFaqs($request);
        $data['is_active'] = $request->boolean('is_active');

        $page->fill(collect($data)->only([
            'slug', 'title', 'short', 'hero', 'icon', 'lede',
            'sections', 'faqs', 'is_active', 'sort_order',
        ])->all());
        $page->save();

        $message = $updating ? 'Page updated.' : 'Page created.';

        return redirect()->route('admin.pages.index')->with('success', $message);
    }

    protected function validated(Request $request, Page $page): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', Rule::unique('pages', 'slug')->ignore($page->id)],
            'short' => ['nullable', 'string', 'max:255'],
            'hero' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:60'],
            'lede' => ['nullable', 'string', 'max:5000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'section_heading.*' => ['nullable', 'string', 'max:190'],
            'section_icon.*' => ['nullable', 'string', 'max:60'],
            'section_body.*' => ['nullable', 'string'],
            'faq_q.*' => ['nullable', 'string', 'max:255'],
            'faq_a.*' => ['nullable', 'string'],
        ]);
    }

    protected function collectSections(Request $request): array
    {
        $heads = $request->input('section_heading', []);
        $icons = $request->input('section_icon', []);
        $bodies = $request->input('section_body', []);

        $sections = [];
        foreach ($heads as $i => $heading) {
            if (trim((string) $heading) === '' && trim((string) ($bodies[$i] ?? '')) === '') {
                continue;
            }
            $sections[] = [
                'heading' => trim((string) $heading),
                'icon' => trim((string) ($icons[$i] ?? 'circle-check')) ?: 'circle-check',
                'body' => trim((string) ($bodies[$i] ?? '')),
            ];
        }
        return $sections;
    }

    protected function collectFaqs(Request $request): array
    {
        $qs = $request->input('faq_q', []);
        $as = $request->input('faq_a', []);
        $faqs = [];
        foreach ($qs as $i => $q) {
            if (trim((string) $q) === '' && trim((string) ($as[$i] ?? '')) === '') {
                continue;
            }
            $faqs[] = ['q' => trim((string) $q), 'a' => trim((string) ($as[$i] ?? ''))];
        }
        return $faqs;
    }

    public function toggle(Page $page): RedirectResponse
    {
        $page->update(['is_active' => ! $page->is_active]);
        return back()->with('success', $page->is_active ? 'Page is now live.' : 'Page is now hidden.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        return back()->with('success', 'Page deleted.');
    }

    protected function iconOptions(): array
    {
        return $this->icons;
    }

    protected array $icons = [
        'file-text' => 'File',
        'shield-check' => 'Privacy / Shield',
        'truck' => 'Shipping',
        'rotate-ccw' => 'Refund / Rotate',
        'x-circle' => 'Blocked / Cancel',
        'refresh-cw' => 'Exchange',
        'leaf' => 'Organic',
        'info' => 'Info',
        'help-circle' => 'Help',
        'lock' => 'Lock',
        'scale' => 'Balance / Law',
        'store' => 'Store',
        'users' => 'People',
        'sparkles' => 'Sparkle',
        'heart' => 'Heart',
        'badge-check' => 'Check Badge',
        'milestone' => 'Milestone',
        'scroll-text' => 'Document',
    ];
}