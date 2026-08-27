# Phase 10 — Admin Dashboard Core

**Fitur dasar Admin Dashboard untuk menampilkan ringkasan kondisi perpustakaan, informasi yang membutuhkan perhatian admin, ringkasan aktivitas hari ini, dan quick actions.**

## 1. Dashboard Summary Cards

* [ ] Tambahkan summary cards pada bagian atas Admin Dashboard.

* [ ] Tambahkan 6 summary cards:

  * [ ] **Total Members**
  * [ ] **Active Members**
  * [ ] **Total Books**
  * [ ] **Available Books**
  * [ ] **Active Borrowings**
  * [ ] **Overdue Borrowings**

### Total Members

* [ ] Hitung total user yang memiliki role **Member**.

* [ ] Jangan menghitung user dengan role Admin.

### Active Members

* [ ] Hitung member dengan `member_status = active`.

### Total Books

* [ ] Hitung total judul buku pada tabel `books`.

* [ ] Gunakan jumlah record `books`, bukan total `stock`.

### Available Books

* [ ] Hitung total stok buku yang tersedia.

* [ ] Gunakan `books.stock` sebagai dasar perhitungan stok.

* [ ] Jangan menghitung jumlah judul buku sebagai jumlah buku tersedia.

### Active Borrowings

* [ ] Hitung borrowing yang masih aktif.

* [ ] Gunakan status:

  * [ ] `pending`
  * [ ] `borrowed`
  * [ ] `overdue`

### Overdue Borrowings

* [ ] Hitung borrowing dengan status `overdue`.

* [ ] Tampilkan jumlah overdue secara jelas agar mudah diperhatikan admin.

### General

* [ ] Pastikan seluruh summary card menampilkan `0` jika tidak terdapat data.

* [ ] Gunakan query database yang efisien.

* [ ] Hindari mengambil seluruh data ke collection PHP hanya untuk menghitung statistik.

---

## 2. Needs Attention

* [ ] Tambahkan section **Needs Attention** setelah Summary Cards.

* [ ] Section ini digunakan untuk menampilkan kondisi yang membutuhkan tindakan admin.

* [ ] Tambahkan 4 item:

  * [ ] **Pending Borrowings**
  * [ ] **Overdue Borrowings**
  * [ ] **Unpaid Fines**
  * [ ] **Low Stock Books**

### Pending Borrowings

* [ ] Tampilkan jumlah borrowing dengan status `pending`.

* [ ] Jika terdapat pending borrowing, tampilkan jumlahnya.

* [ ] Tambahkan link/action menuju halaman Borrowings.

### Overdue Borrowings

* [ ] Tampilkan jumlah borrowing dengan status `overdue`.

* [ ] Tambahkan link/action menuju halaman Borrowings.

### Unpaid Fines

* [ ] Hitung fine dengan status `unpaid`.

* [ ] Tampilkan jumlah unpaid fine.

* [ ] Tampilkan total nominal unpaid fine.

* [ ] Tambahkan link/action menuju halaman Fines.

### Low Stock Books

* [ ] Tampilkan jumlah buku dengan stock rendah.

* [ ] Gunakan threshold stock yang konsisten dengan aplikasi.

* [ ] Buku dengan stock `0` harus tetap termasuk dalam kondisi yang membutuhkan perhatian.

* [ ] Tambahkan link/action menuju halaman Books.

### Empty Condition

* [ ] Jika tidak ada item yang membutuhkan perhatian, tampilkan kondisi:

  **Everything looks good.**

* [ ] Jangan menampilkan section kosong tanpa informasi.

---

## 3. Today's Summary

* [ ] Tambahkan section **Today's Summary**.

* [ ] Tampilkan ringkasan aktivitas pada hari berjalan.

* [ ] Tambahkan 5 informasi:

  * [ ] **New Members**
  * [ ] **New Borrowings**
  * [ ] **Books Returned**
  * [ ] **New Fines**
  * [ ] **Library Visits**

### New Members

* [ ] Hitung member yang bergabung pada hari ini.

* [ ] Gunakan `joined_at` sebagai acuan jika tersedia.

### New Borrowings

* [ ] Hitung borrowing dengan `borrow_date` pada hari ini.

### Books Returned

* [ ] Hitung borrowing yang memiliki `returned_at` pada hari ini.

### New Fines

* [ ] Hitung fine yang dibuat pada hari ini.

* [ ] Gunakan `fines.created_at`.

### Library Visits

* [ ] Hitung attendance berdasarkan `attendances.check_in_at` pada hari ini.

* [ ] Hitung seluruh record check-in hari ini.

### General

* [ ] Pastikan seluruh statistik menampilkan `0` jika tidak terdapat data.

* [ ] Gunakan timezone aplikasi untuk menentukan hari berjalan.

---

## 4. Quick Actions

* [ ] Tambahkan section **Quick Actions** pada Admin Dashboard.

* [ ] Tambahkan action:

  * [ ] **Add Book**
  * [ ] **Add Member**
  * [ ] **Process Borrowing**
  * [ ] **Process Return**
  * [ ] **View Overdue**
  * [ ] **View Fines**

* [ ] Setiap action harus mengarah ke halaman atau route yang sudah tersedia.

* [ ] Jangan membuat fitur baru di luar kebutuhan Quick Actions.

* [ ] Pastikan action hanya ditampilkan jika user memiliki permission yang sesuai.

---

## 5. Dashboard Layout

* [ ] Susun layout dashboard dengan urutan:

  * [ ] Summary Cards
  * [ ] Needs Attention
  * [ ] Today's Summary
  * [ ] Quick Actions

* [ ] Gunakan grid layout yang responsive.

* [ ] Summary Cards berada pada bagian paling atas setelah page header.

* [ ] **Needs Attention** harus mudah terlihat dan tidak kalah menonjol dari statistik biasa.

* [ ] Gunakan card dengan style yang konsisten dengan Admin Dashboard yang sudah ada.

### Responsive

* [ ] Pastikan dashboard responsive pada desktop.

* [ ] Pastikan dashboard responsive pada tablet.

* [ ] Pastikan dashboard responsive pada mobile.

* [ ] Pastikan card tidak menyebabkan horizontal overflow.

---

## 6. Query & Database

* [ ] Gunakan query database untuk seluruh statistik dashboard.

* [ ] Jangan mengambil seluruh record kemudian melakukan filtering atau counting menggunakan PHP collection.

* [ ] Gunakan aggregate query seperti `count()` dan `sum()` jika sesuai kebutuhan.

* [ ] Gunakan relasi yang sudah tersedia pada model.

* [ ] Hindari N+1 query.

* [ ] Jangan mengubah struktur tabel database.

* [ ] Jangan menambahkan field baru hanya untuk kebutuhan dashboard.

* [ ] Gunakan timezone aplikasi untuk seluruh perhitungan tanggal.

---

## 7. Empty State

* [ ] Pastikan dashboard tetap terlihat baik ketika database masih kosong.

* [ ] Summary cards tetap menampilkan `0`.

* [ ] Today's Summary tetap menampilkan `0`.

* [ ] Needs Attention menampilkan kondisi tidak ada masalah jika seluruh data kosong.

* [ ] Quick Actions tetap dapat digunakan meskipun belum terdapat data.

* [ ] Jangan menampilkan error karena data atau relasi belum tersedia.

---

## 8. Permission & Access

* [ ] Pastikan Admin Dashboard hanya dapat diakses oleh user yang memiliki permission yang sesuai.

* [ ] Jangan menampilkan dashboard admin kepada Member.

* [ ] Gunakan mekanisme permission dari **Spatie Laravel Permission** yang sudah digunakan aplikasi.

* [ ] Jangan membuat sistem permission baru khusus untuk dashboard.

* [ ] Pastikan Quick Actions mengikuti permission user.

---

## 9. UI Integration

* [ ] Pertahankan layout, typography, spacing, button style, dan card style yang sudah digunakan aplikasi.

* [ ] Gunakan icon yang sudah digunakan pada aplikasi.

* [ ] Jangan menambahkan chart pada phase ini.

* [ ] Jangan menambahkan filter dashboard pada phase ini.

* [ ] Jangan menambahkan analytics detail pada phase ini.

* [ ] Pastikan dashboard tetap sederhana dan mudah dipindai oleh admin.

* [ ] Pastikan seluruh section memiliki hierarchy visual yang jelas.
