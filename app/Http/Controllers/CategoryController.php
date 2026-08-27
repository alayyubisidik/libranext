<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\AlertService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $sort = $request->input('sort', 'latest');

        $categories = Category::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->withCount('books')
            ->when($sort === 'name_asc', fn ($query) => $query->orderBy('name', 'asc'))
            ->when($sort === 'name_desc', fn ($query) => $query->orderBy('name', 'desc'))
            ->when($sort === 'books_asc', fn ($query) => $query->orderBy('books_count', 'asc'))
            ->when($sort === 'books_desc', fn ($query) => $query->orderBy('books_count', 'desc'))
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when($sort === 'latest' || !$sort, fn ($query) => $query->latest())
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name'        => ['required', 'string', 'max:255', 'unique:categories,name'],
                'description' => ['nullable', 'string'],
                'status'      => ['required', 'in:active,inactive'],
            ],
            [
                'name.required' => 'Nama kategori wajib diisi.',
                'name.unique'   => 'Nama kategori sudah digunakan.',
            ]
        );

        Category::create($validated);

        AlertService::created('Category created successfully');

        return to_route('dashboard.categories.index');
    }

    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate(
            [
                'name'        => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
                'description' => ['nullable', 'string'],
                'status'      => ['required', 'in:active,inactive'],
            ],
            [
                'name.required' => 'Nama kategori wajib diisi.',
                'name.unique'   => 'Nama kategori sudah digunakan.',
            ]
        );

        $category->update($validated);

        AlertService::updated('Category updated successfully');

        return to_route('dashboard.categories.index');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->exists()) {
            AlertService::error('Cannot delete category because it is still used by books.');
            return back();
        }

        $category->delete();

        AlertService::deleted('Category deleted successfully');

        return to_route('dashboard.categories.index');
    }

    public function bulkStatus(Request $request)
    {
        $validated = $request->validate([
            'ids'    => ['required', 'array'],
            'ids.*'  => ['integer', 'exists:categories,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Category::whereIn('id', $validated['ids'])->update(['status' => $validated['status']]);

        AlertService::updated('Categories status updated successfully.');

        return back();
    }
}
