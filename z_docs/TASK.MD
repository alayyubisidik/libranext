## Phase 8 v2 — Activity Logs Enhancement

**Fitur peningkatan Activity Logs pada Admin Dashboard untuk mempermudah pencarian, filtering, sorting, dan melihat perubahan data.**

### Filter Event

- [ ] Tambahkan filter **Event** pada Activity Logs.
- [ ] Sediakan pilihan:
  - [ ] All Events
  - [ ] Created
  - [ ] Updated
  - [ ] Deleted
- [ ] Filter berdasarkan nilai `event`.

### Filter User

- [ ] Tambahkan filter **User** pada Activity Logs.
- [ ] Sediakan pilihan **All Users**.
- [ ] Tampilkan user yang memiliki activity log pada pilihan filter.
- [ ] Filter berdasarkan `causer_id`.

### Filter Model Type

- [ ] Tambahkan filter **Model Type** pada Activity Logs.
- [ ] Sediakan pilihan **All Types**.
- [ ] Tampilkan model yang memiliki activity log pada pilihan filter.
- [ ] Filter berdasarkan `subject_type`.

### Sorting

- [ ] Tambahkan fitur **Sort** pada Activity Logs.
- [ ] Sediakan pilihan:
  - [ ] Newest → Oldest
  - [ ] Oldest → Newest
- [ ] Sorting berdasarkan `created_at`.
- [ ] Sorting dapat digunakan bersamaan dengan search dan filter.

### Detail Changes

- [ ] Tampilkan detail perubahan data pada Activity Log yang memiliki `attribute_changes`.
- [ ] Tampilkan nilai sebelum perubahan.
- [ ] Tampilkan nilai setelah perubahan.
- [ ] Tampilkan nama attribute yang berubah.
- [ ] Jika tidak terdapat perubahan data, tetap tampilkan informasi bahwa tidak ada changes.
- [ ] Ganti tampilan **No details** dengan action untuk melihat perubahan jika tersedia.

### Pagination

- [ ] Tambahkan pagination pada Activity Logs.
- [ ] Tampilkan jumlah data dan halaman saat ini.
- [ ] Pastikan search, filter, dan sorting tetap dipertahankan ketika berpindah halaman.
- [ ] Gunakan pagination Laravel pada query Activity Logs.

### UI Integration

- [ ] Tambahkan filter dan sorting pada area toolbar Activity Logs.
- [ ] Pertahankan fitur Search dan Clear yang sudah tersedia.
- [ ] Pastikan seluruh filter dapat digunakan secara bersamaan.
- [ ] Pastikan Activity Logs tetap bersifat **read-only** dan tidak memiliki bulk action.
- [ ] Pastikan tampilan tetap responsive.