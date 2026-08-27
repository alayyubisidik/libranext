<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Services\AlertService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $status = $request->input('status');
        $stock = $request->input('stock');
        $sort = $request->input('sort', 'latest');

        $books = Book::query()
            ->with(['category', 'media'])
            ->withCount(['borrowings as borrowed_count' => function ($q) {
                $q->where('status', 'borrowed');
            }])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($stock === 'in_stock', fn ($query) => $query->where('stock', '>', 0))
            ->when($stock === 'out_of_stock', fn ($query) => $query->where('stock', 0))
            ->when($sort === 'title_asc', fn ($query) => $query->orderBy('title', 'asc'))
            ->when($sort === 'title_desc', fn ($query) => $query->orderBy('title', 'desc'))
            ->when($sort === 'stock_asc', fn ($query) => $query->orderBy('stock', 'asc'))
            ->when($sort === 'stock_desc', fn ($query) => $query->orderBy('stock', 'desc'))
            ->when($sort === 'year_desc', fn ($query) => $query->orderBy('publication_year', 'desc'))
            ->when($sort === 'year_asc', fn ($query) => $query->orderBy('publication_year', 'asc'))
            ->when($sort === 'status_asc', fn ($query) => $query->orderBy('status', 'asc'))
            ->when($sort === 'status_desc', fn ($query) => $query->orderBy('status', 'desc'))
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when($sort === 'latest' || !$sort, fn ($query) => $query->latest())
            ->paginate(10)
            ->withQueryString();

        $categories = Category::where('status', 'active')->orderBy('name')->get();

        return view('dashboard.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('dashboard.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => ['required', 'exists:categories,id'],
            'isbn'             => ['nullable', 'string', 'max:20', 'unique:books,isbn'],
            'title'            => ['required', 'string', 'max:255'],
            'author'           => ['required', 'string', 'max:255'],
            'publisher'        => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stock'            => ['required', 'integer', 'min:0'],
            'description'      => ['nullable', 'string'],
            'status'           => ['required', 'in:active,inactive'],
            'cover'            => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        $book = Book::create($validated);

        if ($request->hasFile('cover')) {
            $book->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        AlertService::created('Book created successfully');

        return to_route('dashboard.books.index');
    }

    public function edit(Book $book)
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('dashboard.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'category_id'      => ['required', 'exists:categories,id'],
            'isbn'             => ['nullable', 'string', 'max:20', 'unique:books,isbn,' . $book->id],
            'title'            => ['required', 'string', 'max:255'],
            'author'           => ['required', 'string', 'max:255'],
            'publisher'        => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stock'            => ['required', 'integer', 'min:0'],
            'description'      => ['nullable', 'string'],
            'status'           => ['required', 'in:active,inactive'],
            'cover'            => ['nullable', 'image', 'max:2048'],
            'remove_cover'     => ['nullable', 'boolean'],
        ]);

        $book->update($validated);

        if ($request->boolean('remove_cover')) {
            $book->clearMediaCollection('cover');
        } elseif ($request->hasFile('cover')) {
            $book->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        AlertService::updated('Book updated successfully');

        return to_route('dashboard.books.index');
    }

    public function destroy(Book $book)
    {
        if ($book->borrowings()->where('status', 'borrowed')->exists()) {
            AlertService::error('Cannot delete book because it has active borrowings.');
            return back();
        }

        $book->delete();

        AlertService::deleted('Book deleted successfully');

        return to_route('dashboard.books.index');
    }

    public function updateStock(Request $request, Book $book)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:add,remove'],
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['action'] === 'add') {
            $book->increment('stock', $validated['amount']);
        } else {
            $newStock = $book->stock - $validated['amount'];
            if ($newStock < 0) {
                AlertService::error('Stock cannot be negative.');
                return back();
            }
            $book->decrement('stock', $validated['amount']);
        }

        AlertService::updated('Stock updated successfully.');

        return back();
    }

    public function bulkStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:books,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Book::whereIn('id', $validated['ids'])->update(['status' => $validated['status']]);

        AlertService::updated('Books status updated successfully.');

        return back();
    }
}
