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

        $categories = Category::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->withCount('books')
            ->latest()
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
}
