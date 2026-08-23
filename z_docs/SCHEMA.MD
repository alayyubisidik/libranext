# Libranext Database Schema

## 1. Overview

Database menggunakan MySQL 8+.

### Main Tables

```text
users
categories
books
borrowings
fines
payments
attendances
permissions
roles
model_has_permissions
model_has_roles
role_has_permissions
media
activity_log
password_reset_tokens
```

## 2. Users

Admin dan Member menggunakan satu tabel `users`.
Role ditentukan oleh Spatie Permission, bukan kolom `role`.

| Column            | Type         | Notes                     |
| ----------------- | ------------ | ------------------------- |
| id                | BIGINT       | PK                        |
| name              | VARCHAR(255) | Required                  |
| email             | VARCHAR(255) | Unique                    |
| email_verified_at | TIMESTAMP    | Nullable                  |
| password          | VARCHAR(255) | Required                  |
| member_code       | VARCHAR(20)  | Unique, nullable          |
| phone             | VARCHAR(20)  | Nullable                  |
| address           | TEXT         | Nullable                  |
| date_of_birth     | DATE         | Nullable                  |
| member_status     | ENUM         | active/inactive, nullable |
| joined_at         | DATE         | Nullable                  |
| remember_token    | VARCHAR(100) | Nullable                  |
| created_at        | TIMESTAMP    |                           |
| updated_at        | TIMESTAMP    |                           |

Member fields are nullable because Admin does not use them.

## 3. Categories

| Column      | Type         | Notes           |
| ----------- | ------------ | --------------- |
| id          | BIGINT       | PK              |
| name        | VARCHAR(255) | Unique          |
| slug        | VARCHAR(255) | Unique          |
| description | TEXT         | Nullable        |
| status      | ENUM         | active/inactive |
| created_at  | TIMESTAMP    |                 |
| updated_at  | TIMESTAMP    |                 |

## 4. Books

| Column           | Type         | Notes              |
| ---------------- | ------------ | ------------------ |
| id               | BIGINT       | PK                 |
| category_id      | BIGINT       | FK → categories.id |
| isbn             | VARCHAR(20)  | Unique, nullable   |
| title            | VARCHAR(255) | Required           |
| author           | VARCHAR(255) | Required           |
| publisher        | VARCHAR(255) | Nullable           |
| publication_year | YEAR         | Nullable           |
| stock            | INT UNSIGNED | Total stock        |
| description      | TEXT         | Nullable           |
| status           | ENUM         | active/inactive    |
| created_at       | TIMESTAMP    |                    |
| updated_at       | TIMESTAMP    |                    |

Book cover is stored using Spatie Media Library.

Available stock is calculated:

```text
available_stock = stock - active borrowings
```

## 5. Borrowings

One row represents one book borrowed by one member.

| Column       | Type        | Notes                     |
| ------------ | ----------- | ------------------------- |
| id           | BIGINT      | PK                        |
| user_id      | BIGINT      | FK → users.id             |
| book_id      | BIGINT      | FK → books.id             |
| processed_by | BIGINT      | FK → users.id, nullable   |
| borrow_code  | VARCHAR(50) | Unique                    |
| borrow_date  | DATE        | Required                  |
| due_date     | DATE        | Required                  |
| returned_at  | DATETIME    | Nullable                  |
| status       | ENUM        | borrowed/returned/overdue |
| created_at   | TIMESTAMP   |                           |
| updated_at   | TIMESTAMP   |                           |

`user_id` = member who borrows.
`processed_by` = admin who processes the return.

## 6. Fines

One borrowing can have at most one fine.

| Column       | Type          | Notes                     |
| ------------ | ------------- | ------------------------- |
| id           | BIGINT        | PK                        |
| borrowing_id | BIGINT        | Unique FK → borrowings.id |
| rate_per_day | DECIMAL(10,2) | Default 500               |
| overdue_days | INT UNSIGNED  | Default 0                 |
| amount       | DECIMAL(10,2) | Default 0                 |
| status       | ENUM          | unpaid/paid/waived        |
| created_at   | TIMESTAMP     |                           |
| updated_at   | TIMESTAMP     |                           |

The fine rate and amount are stored as a snapshot so historical fines are not changed if the default rate changes.

Default:

```text
rate_per_day = 500
```

## 7. Payments

A payment belongs to a fine.
Payment supports both cash and Midtrans.

| Column         | Type          | Notes                                 |
| -------------- | ------------- | -------------------------------------- |
| id             | BIGINT        | PK                                     |
| fine_id        | BIGINT        | FK → fines.id                          |
| user_id        | BIGINT        | FK → users.id                          |
| order_id       | VARCHAR(100)  | Unique                                 |
| transaction_id | VARCHAR(100)  | Nullable                               |
| method         | ENUM          | cash/midtrans                          |
| payment_type   | VARCHAR(50)   | Nullable                               |
| amount         | DECIMAL(10,2) | Required                               |
| status         | ENUM          | pending/paid/failed/expired/cancelled  |
| paid_at        | DATETIME      | Nullable                               |
| raw_response   | JSON          | Nullable                               |
| created_at     | TIMESTAMP     |                                         |
| updated_at     | TIMESTAMP     |                                         |

### Cash Payment

```text
method = cash
status = pending
        ↓
Admin confirms
        ↓
status = paid
```

`transaction_id`, `payment_type`, and `raw_response` are nullable for cash payments.

### Midtrans Payment

```text
method = midtrans
status = pending
        ↓
Midtrans payment
        ↓
payment notification
        ↓
status = paid
```

`transaction_id`, `payment_type`, and `raw_response` store Midtrans information.

A successful payment changes the related fine:

```text
fines.status = paid
```

## 8. Attendances

Digunakan untuk fitur absen kunjungan member ke perpustakaan.
Satu baris merepresentasikan satu kali absen (check-in) member.

| Column      | Type      | Notes                   |
| ----------- | --------- | ------------------------ |
| id          | BIGINT    | PK                        |
| user_id     | BIGINT    | FK → users.id (member)    |
| check_in_at | DATETIME  | Required                  |
| created_at  | TIMESTAMP |                            |
| updated_at  | TIMESTAMP |                            |

## 9. Spatie Permission

Use the default tables from Spatie Laravel Permission:

```text
permissions
roles
model_has_permissions
model_has_roles
role_has_permissions
```

Roles:

```text
admin
member
```

User role is determined through `model_has_roles`.

## 10. Media Library

Use Spatie Media Library's `media` table.

Used for:

```text
Book cover
Member profile photo
```

No image path column is required in `books` or `users`.

## 11. Activity Log

Use Spatie Activitylog's `activity_log` table.

Log important actions such as:

```text
Create book
Update book
Delete book
Create member
Update member
Borrow book
Return book
Confirm cash payment
Payment status update
Member attendance check-in
```

## 12. Password Reset

Use Laravel Breeze's:

```text
password_reset_tokens
```

## 13. Relationships

```text
User
 ├── hasMany Borrowings
 ├── hasMany Payments
 ├── hasMany Attendances
 └── belongsToMany Roles

Category
 └── hasMany Books

Book
 ├── belongsTo Category
 └── hasMany Borrowings

Borrowing
 ├── belongsTo User
 ├── belongsTo Book
 ├── belongsTo Admin (processed_by)
 └── hasOne Fine

Fine
 ├── belongsTo Borrowing
 └── hasMany Payments

Payment
 ├── belongsTo Fine
 └── belongsTo User

Attendance
 └── belongsTo User
```

## 14. Important Rules

* Admin and Member share the `users` table.
* Role is managed by Spatie Permission.
* Member-only fields in `users` are nullable.
* One borrowing represents one book.
* One borrowing can have one fine.
* One fine can have multiple payment attempts.
* A paid fine cannot be paid again.
* Cash payment requires Admin confirmation.
* Midtrans payment requires successful payment confirmation.
* Book cover and profile photo use Media Library.
* Available stock is calculated, not stored separately.
* Fine rate defaults to Rp500/day.
* One attendance row represents one check-in visit by a member.