# Libranext Task

## Phase 1 — Project Setup

* [x] Create Laravel 13 project.
* [x] Configure `.env` for MySQL.
* [x] Configure application name and basic settings.
* [x] Install and configure Laravel Breeze.
* [x] Install and configure Spatie Permission.
* [x] Install and configure Spatie Media Library.
* [x] Install and configure Spatie Activitylog.
* [x] Install Eloquent Sluggable.
* [x] Install Midtrans PHP SDK.
* [x] Install Laravel DomPDF.
* [x] Install Laravel Excel.
* [x] Install PHP Flasher Notyf.
* [x] Configure Guzzle if required.
* [x] Configure Debugbar, Pint, Pail, PHPUnit, and other development tools.

## Phase 2 — Database

* [x] Create `users` table.
* [x] Create `password_reset_tokens` table.
* [x] Create `categories` table.
* [x] Create `books` table.
* [x] Create `borrowings` table.
* [x] Create `fines` table.
* [x] Create `payments` table.
* [x] Publish/create Spatie Permission tables.
* [x] Publish/create Spatie Media Library table.
* [x] Publish/create Spatie Activitylog table.
* [x] Add all foreign keys and indexes.
* [ ] Verify database structure against `schema.md`.

## Phase 3 — Models & Relationships

* [x] Create/configure User model.
* [x] Create Category model.
* [x] Create Book model.
* [x] Create Borrowing model.
* [x] Create Fine model.
* [x] Create Payment model.
* [x] Configure model relationships.
* [x] Configure casts and enums where appropriate.
* [x] Configure Media Library for User.
* [x] Configure Media Library for Book.
* [ ] Configure Spatie roles and permissions.

## Phase 4 — Authentication & Authorization

* [x] Configure login.
* [x] Configure logout.
* [x] Configure password reset.
* [x] Remove public registration.
* [x] Create initial admin setup flow.
* [x] Prevent admin setup after an admin exists.
* [x] Create `admin` role.
* [x] Create `member` role.
* [ ] Configure required permissions.
* [x] Protect admin routes.
* [x] Protect member routes.
* [x] Verify role-based access.

## Phase 5 — Admin Layout & Dashboard

* [x] Create authenticated application layout.
* [x] Create sidebar.
* [x] Create topbar.
* [x] Create navigation based on permissions.
* [x] Create dashboard page.
* [x] Add total books statistic.
* [x] Add total members statistic.
* [x] Add active borrowing statistic.
* [x] Add overdue borrowing statistic.
* [x] Add available stock statistic.
* [x] Add unpaid fine statistic.

## Phase 6 — Category Management

* [x] Create Category CRUD.
* [x] Add category search/filter if needed.
* [x] Generate category slug.
* [x] Add category status.
* [x] Prevent deleting categories that are still used by books.
* [x] Add success/error alerts.
* [x] Add validation.
* [x] Add pagination where needed.

## Phase 7 — Book Management

* [x] Create Book CRUD.
* [x] Add category selection.
* [x] Add ISBN.
* [x] Add author.
* [x] Add publisher.
* [x] Add publication year.
* [x] Add stock.
* [x] Add description.
* [x] Add book status.
* [x] Add book cover upload.
* [x] Add book cover replacement.
* [x] Add book cover deletion.
* [x] Display available stock.
* [x] Prevent deleting books with active borrowings.
* [x] Add search and filters.
* [x] Add pagination.
* [x] Add validation.
* [x] Add success/error alerts.

## Phase 8 — Member Management

* [x] Create Member CRUD.
* [x] Generate unique member code.
* [x] Add member profile fields.
* [x] Add member profile photo.
* [x] Add active/inactive status.
* [x] Create member login credentials.
* [x] Prevent inactive members from borrowing.
* [x] Show member borrowing history.
* [x] Show member unpaid fines.
* [x] Add search and filters.
* [x] Add pagination.
* [x] Add validation.
* [x] Add success/error alerts.

## Phase 9 — Borrowing

* [x] Create borrowing page.
* [x] Select member.
* [x] Select available book.
* [x] Set borrow date.
* [x] Set due date.
* [x] Generate unique borrow code.
* [x] Validate member status.
* [x] Validate book status.
* [x] Validate book availability.
* [x] Validate maximum 3 active borrowings.
* [x] Prevent borrowing the same active book.
* [x] Create borrowing record.
* [x] Update stock logic.
* [x] Add borrowing list.
* [x] Add borrowing detail.
* [x] Add search and filters.
* [x] Add pagination.
* [x] Use database transaction where required.
* [x] Add success/error alerts.

## Phase 10 — Returning & Fine

* [x] Create return flow.
* [x] Mark borrowing as returned.
* [x] Store `returned_at`.
* [x] Set `processed_by`.
* [x] Calculate overdue days.
* [x] Calculate fine using Rp500/day.
* [x] Create fine record when required.
* [x] Restore book availability.
* [x] Prevent returning an already returned borrowing.
* [x] Display fine information.
* [x] Use database transaction.
* [x] Add success/error alerts.

## Phase 11 — Fine Management

* [x] Create unpaid fine list.
* [x] Create fine detail.
* [x] Display borrowing information.
* [x] Display overdue days.
* [x] Display fine amount.
* [x] Display payment status.
* [x] Support waived fines if required.
* [x] Prevent payment of already paid fines.

## Phase 12 — Cash Payment

* [x] Create cash payment flow.
* [x] Allow Admin to record cash payment.
* [x] Create payment record with `method = cash`.
* [x] Set payment status to pending before confirmation.
* [x] Allow Admin to confirm cash payment.
* [x] Set payment status to paid after confirmation.
* [x] Set `paid_at`.
* [x] Update related fine to paid.
* [x] Prevent duplicate payment.
* [x] Add payment history.
* [x] Add success/error alerts.

## Phase 13 — Midtrans Payment

* [x] Configure Midtrans credentials.
* [x] Configure sandbox/production environment.
* [x] Create Midtrans payment request.
* [x] Generate unique payment `order_id`.
* [x] Create pending payment record.
* [x] Display Midtrans payment interface.
* [x] Handle Midtrans payment result.
* [x] Create notification/webhook endpoint.
* [x] Verify Midtrans notification.
* [x] Update payment status.
* [x] Store transaction ID.
* [x] Store payment type.
* [x] Store relevant Midtrans response.
* [x] Update fine to paid only after successful payment confirmation.
* [x] Handle failed payment.
* [x] Handle expired payment.
* [x] Handle cancelled payment.
* [x] Prevent duplicate payment.
* [x] Test complete payment flow in sandbox.

## Phase 14 — Member Dashboard

* [x] Create member dashboard.
* [x] Display member information.
* [x] Display active borrowings.
* [x] Display borrowing history.
* [x] Display unpaid fines.
* [x] Display payment status.
* [x] Add online fine payment.
* [x] Restrict member access to admin features.

## Phase 15 — Reports

* [x] Create borrowing report.
* [x] Create returning report.
* [x] Create fine report.
* [x] Create payment report.
* [x] Add date filters.
* [x] Add relevant status filters.
* [x] Add PDF export.
* [x] Add Excel export.

## Phase 16 — Activity Log

* [x] Configure activity logging.
* [x] Log important book actions.
* [x] Log borrowing actions.
* [x] Log returning actions.
* [x] Log payment cash and online
* [x] Create activity log list for Admin.
* [x] Add filters if needed.

## Phase 17 — Attendance Feature

Fitur absen kunjungan member ke perpustakaan. Member **tidak perlu login** untuk melakukan absen — akses melalui tombol khusus di halaman login.

### Database & Model

* [ ] Create `attendances` table migration (`user_id`, `check_in_at`, timestamps only).
* [ ] Add foreign key `user_id` → `users.id`.
* [ ] Create `Attendance` model.
* [ ] Add `attendances()` relationship (hasMany) on `User` model.
* [ ] Add `belongsTo(User)` relationship on `Attendance` model.

### Entry Point (Login Page)

* [ ] Add "Absen" button on the login page.
* [ ] Button links to a public attendance route (guest-accessible, no auth required).

### Attendance Search Page

* [ ] Create public route + controller for the attendance page (outside `auth` middleware).
* [ ] Create search form input (name or member code).
* [ ] Implement live/AJAX search matching `name` LIKE or `member_code` LIKE.
* [ ] Restrict search to users with the `member` role only (exclude admin accounts).
* [ ] Restrict search results to `member_status = active` only.
* [ ] Display list of matching members (name, member code, photo thumbnail).
* [ ] Handle "no matching member found" empty state.
* [ ] Limit number of search results displayed (e.g. max 10).

### Member Detail & Confirmation

* [ ] On clicking a result, show member detail (photo, name, member code, status).
* [ ] Add "Absen" confirmation button on the detail view.
* [ ] Validate member is still active before creating attendance record.
* [ ] Create `attendance` record with `user_id` and `check_in_at = now()` on confirmation.
* [ ] Show success confirmation screen/message after absen recorded.
* [ ] Provide a way to go back to search if the wrong member was selected.

### Security & Abuse Prevention

* [ ] Add rate limiting/throttle on the public attendance route.
* [ ] Ensure no sensitive data (email, phone, address, password) is exposed on the public page.
* [ ] Add basic validation to prevent empty/invalid search submissions.

### Admin Side

* [ ] Create attendance list page for Admin (with date filter).
* [ ] Add search/filter by member on the attendance list.
* [ ] Add pagination to the attendance list.
* [ ] Log attendance check-in to `activity_log`.

### UI

* [ ] Design attendance page following the light and blue theme.
* [ ] Ensure attendance page is responsive (usable on tablet/kiosk device).

## Phase 18 — Testing

* [ ] Test authentication.
* [ ] Test admin setup.
* [ ] Test role permissions.
* [ ] Test category CRUD.
* [ ] Test book CRUD.
* [ ] Test member CRUD.
* [ ] Test borrowing.
* [ ] Test maximum borrowing limit.
* [ ] Test book availability.
* [ ] Test returning.
* [ ] Test fine calculation.
* [ ] Test cash payment.
* [ ] Test Midtrans payment.
* [ ] Test failed/expired payments.
* [ ] Test reports.
* [ ] Test critical authorization rules.
* [ ] Test attendance search (name and member code).
* [ ] Test attendance check-in creation.
* [ ] Test attendance page is not accessible to view sensitive member data.

## Phase 19 — Final Review

* [ ] Verify all PRD features are implemented.
* [ ] Verify implementation against `schema.md`.
* [ ] Verify code follows `code_style.md`.
* [ ] Verify all routes are protected correctly.
* [ ] Verify no out-of-scope features were added.
* [ ] Remove unnecessary code and dependencies.
* [ ] Run Laravel Pint.
* [ ] Run automated tests.
* [ ] Fix remaining bugs.

## Phase 20 — Design Pass

Perform detailed UI work only after all features are functional.

* [ ] Review all pages.
* [ ] Apply consistent layout.
* [ ] Apply light and blue theme.
* [ ] Standardize buttons.
* [ ] Standardize forms.
* [ ] Standardize tables.
* [ ] Standardize cards.
* [ ] Standardize badges.
* [ ] Standardize alerts.
* [ ] Improve empty states.
* [ ] Improve loading states.
* [ ] Review responsive behavior.
* [ ] Remove unnecessary visual complexity.


## Phase 1 (v2) — Default Avatar Feature

Fitur foto profil default untuk User (Admin & Member). Jika saat pembuatan akun tidak ada foto yang diupload, sistem otomatis memakai avatar default dari `public/assets/image/avatar.png`.

### Setup Asset

* [ ] Pastikan file `public/assets/image/avatar.png` tersedia dan ter-commit ke repo.
* [ ] Tentukan path/konstanta terpusat untuk avatar default (hindari hardcode path berulang di banyak file), misal via config (`config('app.default_avatar')`) atau constant di model `User`.

### Model & Logic

* [ ] Tambahkan accessor/helper di model `User` (misal `getAvatarUrlAttribute()` atau method `avatarUrl()`) yang:
  * [ ] Mengembalikan URL media Spatie Media Library jika user punya media avatar.
  * [ ] Mengembalikan path default `avatar.png` jika user **tidak** punya media avatar.
* [ ] Pastikan logic ini reusable untuk dipakai di semua tempat avatar ditampilkan (sidebar, topbar, list member, detail member, dashboard, dsb).

### Integrasi — Admin Setup Flow

* [ ] Saat proses setup admin awal (yang tidak ada input upload foto), pastikan admin yang terbentuk otomatis "dianggap" memakai avatar default (tanpa perlu insert media).
* [ ] Verifikasi tampilan topbar/sidebar admin menampilkan avatar default dengan benar setelah setup.

### Integrasi — Member CRUD

* [ ] Saat create member **tanpa** upload foto profil → tidak perlu attach media, cukup andalkan accessor default.
* [ ] Saat edit member: jika user menghapus foto yang sudah ada (tanpa upload foto baru) → sistem fallback ke avatar default.
* [ ] Saat replace foto (upload foto baru menggantikan lama) → media lama dihapus, media baru jadi avatar aktif (behaviour existing tetap dipakai, hanya pastikan fallback default tidak konflik).

### Integrasi — Tampilan (UI)

* [ ] Pastikan semua tempat yang menampilkan foto profil user (list member, detail member, dashboard member, topbar admin, topbar member, halaman absen/attendance jika relevan) memakai accessor/helper avatar terpusat — bukan langsung query media manual.
* [ ] Pastikan tidak ada broken image / path 404 ketika user belum punya foto sama sekali.

### Validasi & Edge Case

* [ ] Tidak boleh mewajibkan upload foto saat create admin/member (field tetap optional).
* [ ] Avatar default tidak boleh tersimpan sebagai media record di Spatie Media Library (cukup fallback di level tampilan, bukan insert ke DB) — hindari duplikasi data.
* [ ] Pastikan avatar default tetap konsisten tampil walau setelah beberapa kali edit profil tanpa upload foto.
