# Libranext Code Style

## 1. General Rules

* Follow Laravel conventions.
* Keep code simple and readable.
* Prefer Laravel built-in features.
* Avoid unnecessary abstractions.
* Do not create Services for simple CRUD operations.
* Use Controllers for request handling and application flow.
* Use Models for relationships, scopes, casts, and model-specific logic.
* Use Form Request only when validation becomes large or reusable.
* Reuse existing helpers and services before creating new ones.

## 2. Controllers

Use standard Laravel resource controller methods:

```text
index
create
store
show
edit
update
destroy
```

Only create methods that are actually needed.

Controller flow:

```text
Request
→ Validate
→ Query / Create / Update / Delete
→ AlertService
→ Redirect or View
```

Keep controllers focused on one resource.

### Example Structure

```php
class BookController extends Controller
{
    public function index(Request $request)
    {
        // Query
        // Filter
        // Pagination

        return view(...);
    }

    public function create()
    {
        return view(...);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([...]);

        $book = Book::create($validated);

        AlertService::created('Book created successfully');

        return to_route('dashboard.books.index');
    }

    public function edit(Book $book)
    {
        return view(...);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([...]);

        $book->update($validated);

        AlertService::updated('Book updated successfully');

        return to_route('dashboard.books.index');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        AlertService::deleted('Book deleted successfully');

        return to_route('dashboard.books.index');
    }
}
```

## 3. Route Model Binding

Use implicit route model binding.

Prefer:

```php
public function edit(Book $book)
```

Instead of:

```php
public function edit($id)
{
    $book = Book::findOrFail($id);
}
```

## 4. Validation

For simple validation, use `$request->validate()` directly.

```php
$validated = $request->validate([
    'title' => ['required', 'string', 'max:255'],
    'author' => ['required', 'string', 'max:255'],
]);
```

Validation messages should be written when custom Indonesian messages are useful.

```php
$validated = $request->validate(
    [
        'title' => ['required', 'string', 'max:255'],
    ],
    [
        'title.required' => 'Judul buku wajib diisi.',
        'title.max' => 'Judul buku maksimal 255 karakter.',
    ]
);
```

Use Form Request when validation becomes complex or reused by multiple actions.

## 5. Query Style

Use Eloquent.

Prefer:

```php
$books = Book::query()
    ->with('category')
    ->when($search, fn ($query) =>
        $query->where('title', 'like', "%{$search}%")
    )
    ->latest()
    ->paginate(10)
    ->withQueryString();
```

Avoid unnecessary raw SQL.

Use eager loading when relationships are displayed.

```php
Book::with('category')->get();
```

Use `withCount()` when only relationship counts are needed.

```php
Book::withCount('borrowings')->get();
```

## 6. Search and Filters

Use `when()` for optional filters.

```php
->when(
    $search,
    fn ($query) => $query->where('title', 'like', "%{$search}%")
)
```

For request strings:

```php
$search = $request->string('search');
$status = $request->string('status');
```

Keep filtering logic inside the query.

## 7. Pagination

Use pagination for management lists.

Default:

```php
->paginate(10)
->withQueryString();
```

Use query string preservation so search and filters remain active when changing pages.

## 8. Redirects

Use named routes with `to_route()`.

Prefer:

```php
return to_route('dashboard.books.index');
```

Instead of:

```php
return redirect('/dashboard/books');
```

Use `back()` when the user should remain on the current page after an unsuccessful action.

## 9. AlertService

Use `AlertService` for user feedback.

Available patterns:

```php
AlertService::created('Book created successfully');

AlertService::updated('Book updated successfully');

AlertService::deleted('Book deleted successfully');

AlertService::error('Something went wrong.');
```

After successful CRUD actions, show the appropriate alert before redirecting.

Example:

```php
$book->update($validated);

AlertService::updated('Book updated successfully');

return to_route('dashboard.books.index');
```

Do not manually create session flash messages when `AlertService` can handle the message.

## 10. Global `user()` Helper

Use the global `user()` helper to access the authenticated user.

Prefer:

```php
$user = user();
```

Do not use:

```php
Auth::user();
```

or:

```php
auth()->user();
```

Example:

```php
$borrowing->update([
    'processed_by' => user()->id,
]);
```

Use `user()` whenever the current authenticated user is required.

## 11. Authorization

Use Laravel authorization mechanisms and Spatie Permission.

Prefer middleware, policies, gates, or permission checks instead of manually checking roles throughout controllers.

Avoid:

```php
if (user()->role === 'admin') {
    ...
}
```

Use permissions/authorization when access control is required.

## 12. File Uploads

Use Spatie Media Library for images.

Example:

```php
if ($request->hasFile('cover')) {
    $book
        ->addMediaFromRequest('cover')
        ->toMediaCollection('cover');
}
```

When replacing an existing image:

```php
if ($request->hasFile('cover')) {
    $book->clearMediaCollection('cover');

    $book
        ->addMediaFromRequest('cover')
        ->toMediaCollection('cover');
}
```

Do not store media paths manually in model columns when Media Library is used.

## 13. Delete Protection

Before deleting a resource, check whether it is still referenced.

Example:

```php
if ($book->borrowings()->where('status', 'borrowed')->exists()) {
    AlertService::error('Book cannot be deleted while it is being borrowed.');

    return back();
}
```

Use database relationships and `exists()` rather than loading unnecessary records.

## 14. Naming

### Controllers

Use singular resource names:

```text
BookController
CategoryController
MemberController
BorrowingController
PaymentController
```

### Methods

Use Laravel resource method names where applicable:

```text
index
create
store
show
edit
update
destroy
```

### Variables

Use descriptive names:

```php
$books
$members
$borrowings
$validated
```

Avoid unclear names:

```php
$x
$data1
$temp
```

## 15. Models

Models should contain:

* Relationships
* Casts
* Accessors/mutators when useful
* Scopes
* Model-specific logic

Example:

```php
public function category()
{
    return $this->belongsTo(Category::class);
}
```

Do not put unrelated business logic into models.

## 16. Business Logic

Keep simple business logic in the Controller when it is short and specific to one action.

Example:

```php
$fine = $overdueDays * 500;
```

If logic becomes complex, reused, or requires multiple steps, extract it into an appropriate class.

Do not create a Service class just to wrap simple CRUD.

## 17. Transactions

Use database transactions when multiple database operations must succeed or fail together.

Example:

```php
DB::transaction(function () use ($validated) {
    // create borrowing
    // update stock
    // create fine
});
```

Particularly important for:

* Borrowing
* Returning
* Fine creation
* Payment confirmation

## 18. Comments

Do not add comments that merely explain obvious code.

Avoid:

```php
// Create book
$book = Book::create($validated);
```

Add comments only when explaining non-obvious business logic or important decisions.

## 19. Formatting

Follow Laravel/PHP conventions:

* 4 spaces for indentation.
* One statement per line.
* Use trailing commas in multiline arrays.
* Keep methods reasonably short.
* Separate logical sections with whitespace.
* Use strict, readable naming.

## 20. Controller Principle

The default approach is:

```text
Simple → Controller + Model

Complex / Reused → Extract dedicated class

Repeated UI feedback → AlertService

Authenticated user → user()

Database consistency → DB::transaction()

File/media → Spatie Media Library
```

Do not introduce architecture that is not needed by the feature.
