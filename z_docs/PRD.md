# Libranext

## 1. Overview

Libranext adalah aplikasi manajemen perpustakaan untuk mengelola buku, kategori, member, peminjaman, pengembalian, denda, dan pembayaran.

## 2. Goal

* Memudahkan pengelolaan perpustakaan.
* Mengelola buku dan member.
* Mengelola proses peminjaman dan pengembalian.
* Menghitung dan mencatat denda.
* Mendukung pembayaran denda secara cash dan online.
* Menyediakan laporan perpustakaan.

## 3. Tech Stack

### Core

- PHP 8.4+
- Laravel 13
- MySQL 8
- Blade
- Tailwind CSS
- Alpine.js

### Authentication

- Laravel Breeze

### Packages

- Spatie Laravel Permission
- Spatie Laravel Media Library
- Spatie Laravel Activitylog
- Cviebrock Eloquent Sluggable
- Midtrans PHP
- Laravel DomPDF
- Laravel Excel
- PHP Flasher Notyf
- Guzzle

### Development

- Laravel Debugbar
- Faker
- Laravel Pail
- Laravel Pint
- Mockery
- Collision
- PHPUnit

Use packages only when required by the defined features.

## 4. Scope

### In Scope

* Authentication
* Admin setup
* Dashboard
* Book management
* Category management
* Member management
* Borrowing
* Returning
* Fine calculation
* Fine payment
* Borrowing history
* Reports
* Activity log

### Out of Scope

* Public book catalog
* User registration
* Online reading
* Mobile application
* Public API
* Real-time notification

## 5. Roles

### Admin

Admin dapat:

* Mengakses dashboard.
* Mengelola buku dan kategori.
* Mengelola member.
* Mengelola peminjaman dan pengembalian.
* Mengelola pembayaran denda.
* Melihat laporan.
* Melihat activity log.

### Member

Member dapat:

* Login.
* Mengakses dashboard.
* Melihat informasi akun.
* Melihat riwayat peminjaman.
* Melihat denda.
* Membayar denda secara online.

## 6. Authentication

Tidak ada public registration.

### Admin

Admin dibuat melalui setup awal.

`Setup → Create Admin → Login`

Setup hanya dapat dilakukan jika belum ada admin.

### Member

Member dibuat oleh Admin.

`Admin → Members → Create Member → Member Login`

Authentication menggunakan email dan password.

## 7. Features

### Dashboard

Menampilkan informasi penting:

* Total books
* Total members
* Active borrowings
* Overdue borrowings
* Available stock
* Unpaid fines

### Books

* List books
* Create book
* Edit book
* Delete book
* Manage stock
* Upload cover
* Assign category
* Store author
* Store ISBN

### Categories

* List categories
* Create category
* Edit category
* Delete category

### Members

* List members
* Create member
* Edit member
* Delete/deactivate member
* View borrowing history
* View unpaid fines

### Borrowing

* Create borrowing
* Select member
* Select available book
* Set due date
* View active borrowings
* View borrowing history

### Returning

* Return book
* Calculate late days
* Calculate fine
* Update borrowing status
* Restore book stock

### Fine Payment

Member dapat membayar denda dengan:

* Cash
* Online payment via Midtrans

Admin dapat:

* Melihat unpaid fines
* Mencatat pembayaran cash
* Melihat payment status
* Melihat payment history

Online payment:

`Member → Unpaid Fine → Pay → Midtrans → Payment Result → Update Status`

Cash payment:

`Member → Pay Cash → Admin Confirms → Payment Status: Paid`

### Reports

* Borrowing report
* Returning report
* Fine report
* Payment report
* Export reports

### Activity Log

Mencatat aktivitas penting yang dilakukan oleh user.

## 8. Business Rules

* Maximum active borrowings per member: **3 books**.
* Default borrowing period: **7 days**.
* Fine: **Rp500 per late day**.
* Book with stock 0 cannot be borrowed.
* Borrowing decreases stock by 1.
* Returning increases stock by 1.
* Only active borrowings can be returned.
* Fine is calculated from due date until return date.
* Member cannot borrow the same book while an active borrowing exists.
* Inactive members cannot create new borrowings.
* Unpaid fines remain unpaid until payment is confirmed.
* Cash payments must be confirmed by Admin.
* Online payments are considered paid only after successful Midtrans payment confirmation.
* A paid fine cannot be paid again.

## 9. Main Flows

### Admin

`Login → Dashboard → Books / Categories / Members / Borrowing / Payments / Reports`

### Create Member

`Members → Create → Save → Member can login`

### Borrow Book

`Borrowing → Select Member → Select Book → Confirm → Stock -1`

### Return Book

`Active Borrowing → Return → Calculate Fine → Complete → Stock +1`

### Pay Fine Online

`Member → Unpaid Fine → Pay → Midtrans → Success → Fine Paid`

### Pay Fine Cash

`Member → Pay Cash → Admin Confirms → Fine Paid`

### Member

`Login → Dashboard → Account / Borrowing History / Fines`

## 10. Constraints

* Keep the implementation simple.
* Follow Laravel conventions.
* Avoid unnecessary abstractions.
* Avoid unnecessary packages.
* Do not implement features outside the scope.
* Prefer straightforward solutions over over-engineering.
* Use Midtrans only for online fine payments.
* Cash payments must be handled by Admin.
