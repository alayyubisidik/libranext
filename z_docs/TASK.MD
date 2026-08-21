# Libranext Task

## Phase 1 — Project Setup

* [ ] Create Laravel 13 project.
* [ ] Configure `.env` for MySQL.
* [ ] Configure application name and basic settings.
* [ ] Install and configure Laravel Breeze.
* [ ] Install and configure Spatie Permission.
* [ ] Install and configure Spatie Media Library.
* [ ] Install and configure Spatie Activitylog.
* [ ] Install Eloquent Sluggable.
* [ ] Install Midtrans PHP SDK.
* [ ] Install Laravel DomPDF.
* [ ] Install Laravel Excel.
* [ ] Install PHP Flasher Notyf.
* [ ] Configure Guzzle if required.
* [ ] Configure Debugbar, Pint, Pail, PHPUnit, and other development tools.

## Phase 2 — Database

* [ ] Create `users` table.
* [ ] Create `password_reset_tokens` table.
* [ ] Create `categories` table.
* [ ] Create `books` table.
* [ ] Create `borrowings` table.
* [ ] Create `fines` table.
* [ ] Create `payments` table.
* [ ] Publish/create Spatie Permission tables.
* [ ] Publish/create Spatie Media Library table.
* [ ] Publish/create Spatie Activitylog table.
* [ ] Add all foreign keys and indexes.
* [ ] Verify database structure against `schema.md`.

## Phase 3 — Models & Relationships

* [ ] Create/configure User model.
* [ ] Create Category model.
* [ ] Create Book model.
* [ ] Create Borrowing model.
* [ ] Create Fine model.
* [ ] Create Payment model.
* [ ] Configure model relationships.
* [ ] Configure casts and enums where appropriate.
* [ ] Configure Media Library for User.
* [ ] Configure Media Library for Book.
* [ ] Configure Spatie roles and permissions.

## Phase 4 — Authentication & Authorization

* [ ] Configure login.
* [ ] Configure logout.
* [ ] Configure password reset.
* [ ] Remove public registration.
* [ ] Create initial admin setup flow.
* [ ] Prevent admin setup after an admin exists.
* [ ] Create `admin` role.
* [ ] Create `member` role.
* [ ] Configure required permissions.
* [ ] Protect admin routes.
* [ ] Protect member routes.
* [ ] Verify role-based access.

## Phase 5 — Admin Layout & Dashboard

* [ ] Create authenticated application layout.
* [ ] Create sidebar.
* [ ] Create topbar.
* [ ] Create navigation based on permissions.
* [ ] Create dashboard page.
* [ ] Add total books statistic.
* [ ] Add total members statistic.
* [ ] Add active borrowing statistic.
* [ ] Add overdue borrowing statistic.
* [ ] Add available stock statistic.
* [ ] Add unpaid fine statistic.

## Phase 6 — Category Management

* [ ] Create Category CRUD.
* [ ] Add category search/filter if needed.
* [ ] Generate category slug.
* [ ] Add category status.
* [ ] Prevent deleting categories that are still used by books.
* [ ] Add success/error alerts.
* [ ] Add validation.
* [ ] Add pagination where needed.

## Phase 7 — Book Management

* [ ] Create Book CRUD.
* [ ] Add category selection.
* [ ] Add ISBN.
* [ ] Add author.
* [ ] Add publisher.
* [ ] Add publication year.
* [ ] Add stock.
* [ ] Add description.
* [ ] Add book status.
* [ ] Add book cover upload.
* [ ] Add book cover replacement.
* [ ] Add book cover deletion.
* [ ] Display available stock.
* [ ] Prevent deleting books with active borrowings.
* [ ] Add search and filters.
* [ ] Add pagination.
* [ ] Add validation.
* [ ] Add success/error alerts.

## Phase 8 — Member Management

* [ ] Create Member CRUD.
* [ ] Generate unique member code.
* [ ] Add member profile fields.
* [ ] Add member profile photo.
* [ ] Add active/inactive status.
* [ ] Create member login credentials.
* [ ] Prevent inactive members from borrowing.
* [ ] Show member borrowing history.
* [ ] Show member unpaid fines.
* [ ] Add search and filters.
* [ ] Add pagination.
* [ ] Add validation.
* [ ] Add success/error alerts.

## Phase 9 — Borrowing

* [ ] Create borrowing page.
* [ ] Select member.
* [ ] Select available book.
* [ ] Set borrow date.
* [ ] Set due date.
* [ ] Generate unique borrow code.
* [ ] Validate member status.
* [ ] Validate book status.
* [ ] Validate book availability.
* [ ] Validate maximum 3 active borrowings.
* [ ] Prevent borrowing the same active book.
* [ ] Create borrowing record.
* [ ] Update stock logic.
* [ ] Add borrowing list.
* [ ] Add borrowing detail.
* [ ] Add search and filters.
* [ ] Add pagination.
* [ ] Use database transaction where required.
* [ ] Add success/error alerts.

## Phase 10 — Returning & Fine

* [ ] Create return flow.
* [ ] Mark borrowing as returned.
* [ ] Store `returned_at`.
* [ ] Set `processed_by`.
* [ ] Calculate overdue days.
* [ ] Calculate fine using Rp500/day.
* [ ] Create fine record when required.
* [ ] Restore book availability.
* [ ] Prevent returning an already returned borrowing.
* [ ] Display fine information.
* [ ] Use database transaction.
* [ ] Add success/error alerts.

## Phase 11 — Fine Management

* [ ] Create unpaid fine list.
* [ ] Create fine detail.
* [ ] Display borrowing information.
* [ ] Display overdue days.
* [ ] Display fine amount.
* [ ] Display payment status.
* [ ] Support waived fines if required.
* [ ] Prevent payment of already paid fines.

## Phase 12 — Cash Payment

* [ ] Create cash payment flow.
* [ ] Allow Admin to record cash payment.
* [ ] Create payment record with `method = cash`.
* [ ] Set payment status to pending before confirmation.
* [ ] Allow Admin to confirm cash payment.
* [ ] Set payment status to paid after confirmation.
* [ ] Set `paid_at`.
* [ ] Update related fine to paid.
* [ ] Prevent duplicate payment.
* [ ] Add payment history.
* [ ] Add success/error alerts.

## Phase 13 — Midtrans Payment

* [ ] Configure Midtrans credentials.
* [ ] Configure sandbox/production environment.
* [ ] Create Midtrans payment request.
* [ ] Generate unique payment `order_id`.
* [ ] Create pending payment record.
* [ ] Display Midtrans payment interface.
* [ ] Handle Midtrans payment result.
* [ ] Create notification/webhook endpoint.
* [ ] Verify Midtrans notification.
* [ ] Update payment status.
* [ ] Store transaction ID.
* [ ] Store payment type.
* [ ] Store relevant Midtrans response.
* [ ] Update fine to paid only after successful payment confirmation.
* [ ] Handle failed payment.
* [ ] Handle expired payment.
* [ ] Handle cancelled payment.
* [ ] Prevent duplicate payment.
* [ ] Test complete payment flow in sandbox.

## Phase 14 — Member Dashboard

* [ ] Create member dashboard.
* [ ] Display member information.
* [ ] Display active borrowings.
* [ ] Display borrowing history.
* [ ] Display unpaid fines.
* [ ] Display payment status.
* [ ] Add online fine payment.
* [ ] Restrict member access to admin features.

## Phase 15 — Reports

* [ ] Create borrowing report.
* [ ] Create returning report.
* [ ] Create fine report.
* [ ] Create payment report.
* [ ] Add date filters.
* [ ] Add relevant status filters.
* [ ] Add PDF export.
* [ ] Add Excel export.

## Phase 16 — Activity Log

* [ ] Configure activity logging.
* [ ] Log important book actions.
* [ ] Log member actions.
* [ ] Log borrowing actions.
* [ ] Log returning actions.
* [ ] Log cash payment confirmation.
* [ ] Log payment status changes.
* [ ] Create activity log list for Admin.
* [ ] Add filters if needed.

## Phase 17 — Validation & Error Handling

* [ ] Review all form validation.
* [ ] Review authorization.
* [ ] Review destructive action confirmations.
* [ ] Review empty states.
* [ ] Review error states.
* [ ] Review payment failure handling.
* [ ] Review database transaction boundaries.
* [ ] Review duplicate action prevention.

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
