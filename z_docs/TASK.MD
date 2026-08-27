# Phase 9 — Attendance Enhancement

**Fitur peningkatan halaman Attendance pada Admin Dashboard untuk menambahkan ringkasan kunjungan dan mengganti filter tanggal tunggal menjadi date range filter.**

## 1. Attendance Summary Cards

* [ ] Tambahkan summary cards pada halaman **Attendance** di antara page header dan area filter.

* [ ] Tambahkan 3 summary cards:

  * [ ] **Today's Visits**
  * [ ] **This Month**
  * [ ] **Unique Members**

* [ ] **Today's Visits**

  * [ ] Hitung total record pada `attendances` untuk tanggal hari ini.
  * [ ] Gunakan field `check_in_at` sebagai acuan tanggal.
  * [ ] Tampilkan jumlah seluruh kunjungan/check-in hari ini.

* [ ] **This Month**

  * [ ] Hitung total record pada `attendances` untuk bulan dan tahun berjalan.
  * [ ] Gunakan field `check_in_at` sebagai acuan.
  * [ ] Tampilkan jumlah seluruh kunjungan pada bulan berjalan.

* [ ] **Unique Members**

  * [ ] Hitung jumlah member unik yang melakukan check-in hari ini.
  * [ ] Gunakan `user_id` sebagai identifier member.
  * [ ] Pastikan satu member yang melakukan beberapa check-in tetap dihitung sebagai satu unique member.

* [ ] Pastikan summary cards menggunakan query database yang efisien.

* [ ] Pastikan summary tetap menampilkan `0` jika tidak ada data.

### UI

* [ ] Pertahankan style visual yang sudah digunakan pada halaman Attendance.
* [ ] Gunakan card dengan desain sederhana dan konsisten dengan dashboard.
* [ ] Jangan menambahkan grafik.
* [ ] Pastikan cards responsive pada desktop, tablet, dan mobile.

---

## 2. Date Range Filter

* [ ] **Hapus filter tanggal tunggal yang saat ini sudah tersedia pada halaman Attendance.**

* [ ] Jangan lagi menggunakan satu input `Tanggal`.

* [ ] Ganti dengan dua input:

  * [ ] **Date From**
  * [ ] **Date To**

Contoh:

```text
Cari Member
[ Nama atau kode member... ]

Date From              Date To
[ mm/dd/yyyy ]         [ mm/dd/yyyy ]

                         [Filter] [Clear]
```

### Date From

* [ ] Tambahkan input tanggal dengan parameter `date_from`.
* [ ] Digunakan sebagai tanggal awal periode pencarian.
* [ ] Jika hanya `date_from` yang diisi, tampilkan attendance mulai dari tanggal tersebut sampai data terbaru.

### Date To

* [ ] Tambahkan input tanggal dengan parameter `date_to`.
* [ ] Digunakan sebagai tanggal akhir periode pencarian.
* [ ] Jika hanya `date_to` yang diisi, tampilkan attendance sampai tanggal tersebut.

### Date From + Date To

* [ ] Jika kedua tanggal diisi, tampilkan attendance dalam rentang `date_from` sampai `date_to`.
* [ ] `date_from` bersifat inclusive.
* [ ] `date_to` bersifat inclusive.
* [ ] Gunakan `attendances.check_in_at` sebagai sumber filtering.
* [ ] Pastikan seluruh waktu pada tanggal `date_to` tetap ikut ditampilkan.

Contoh:

```text
Date From : 2026-08-01
Date To   : 2026-08-23
```

Harus menampilkan seluruh attendance dari:

```text
2026-08-01 00:00:00
sampai
2026-08-23 23:59:59
```

### Validasi

* [ ] Jika `date_from` lebih besar daripada `date_to`, tampilkan validation error yang jelas.
* [ ] Jangan menjalankan query dengan range tanggal yang tidak valid.
* [ ] Jika kedua input kosong, tampilkan data sesuai kondisi default halaman.

---

## 3. Search + Date Range

* [ ] Pertahankan fitur **Cari Member** yang sudah tersedia.

* [ ] Search berdasarkan:

  * [ ] Nama member.
  * [ ] Kode member.

* [ ] Pastikan search dapat digunakan bersamaan dengan:

  * [ ] Date From.
  * [ ] Date To.
  * [ ] Date From + Date To.

* [ ] Pastikan parameter search dan date range tetap dipertahankan ketika melakukan filter.

---

## 4. Clear Filter

* [ ] Tombol **Clear** tetap tersedia.

* [ ] Ketika Clear ditekan:

  * [ ] Kosongkan search member.
  * [ ] Kosongkan Date From.
  * [ ] Kosongkan Date To.
  * [ ] Kembalikan daftar attendance ke kondisi default.
  * [ ] Kembalikan pagination ke halaman pertama jika pagination digunakan.

---

## 5. Query & Database

* [ ] Gunakan `attendances.check_in_at` untuk filtering berdasarkan tanggal.

* [ ] Gunakan relasi `users` untuk pencarian:

  * [ ] `users.name`
  * [ ] `users.member_code`

* [ ] Filtering harus dilakukan langsung pada query database.

* [ ] Jangan mengambil seluruh attendance kemudian melakukan filtering di collection PHP.

* [ ] Jangan mengubah struktur tabel `attendances`.

* [ ] Jangan menambahkan field baru hanya untuk mendukung date range filter.

* [ ] Pastikan timezone mengikuti timezone aplikasi.

---

## 6. UI Integration

* [ ] Pertahankan desain halaman Attendance yang sudah ada.

* [ ] Area filter tetap berada di dalam card seperti desain saat ini.

* [ ] Pada desktop, Date From dan Date To dapat berada dalam satu baris.

* [ ] Pada mobile, field dapat tersusun secara vertikal.

* [ ] Jangan menambahkan filter preset seperti:

  * [ ] Today
  * [ ] Yesterday
  * [ ] Last 7 Days
  * [ ] This Month

* [ ] Filter cukup menggunakan **Date From** dan **Date To**.
