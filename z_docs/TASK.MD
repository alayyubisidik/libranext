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
