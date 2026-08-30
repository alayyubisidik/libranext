# Libranext

Sistem Manajemen Perpustakaan berbasis web yang dibangun dengan Laravel 13, Tailwind CSS, dan Alpine.js. Mendukung dua peran pengguna (admin dan anggota), manajemen peminjaman buku, denda keterlambatan dengan pembayaran online via Midtrans, dan laporan lengkap.

## Fitur Utama

**Admin**
- Manajemen kategori, buku, dan stok
- Manajemen anggota (member)
- Konfirmasi peminjaman dan pengembalian buku
- Manajemen denda — bayar tunai, cicil via Midtrans, atau bebaskan denda
- Laporan peminjaman, pengembalian, denda, dan pembayaran (export PDF & Excel)
- Log aktivitas (audit trail)
- Rekap kehadiran anggota

**Member**
- Dashboard ringkasan peminjaman aktif dan riwayat
- Pengajuan peminjaman buku
- Pembayaran denda online (Midtrans)
- Notifikasi buku hampir jatuh tempo
- Edit profil dan foto avatar

**Umum**
- Kiosk kehadiran (absensi mandiri dengan throttle)
- Notifikasi in-app real-time
- Setup wizard admin pertama kali

## Tech Stack

| Layer | Teknologi |
|---|---|
| Framework | Laravel 13 (PHP 8.3+) |
| Frontend | Blade, Tailwind CSS v3, Alpine.js v3 |
| Build Tool | Vite |
| Database | MySQL |
| Auth & RBAC | Laravel Breeze + Spatie Permission |
| Media | Spatie MediaLibrary |
| Audit | Spatie ActivityLog |
| Payment | Midtrans |
| PDF Export | barryvdh/laravel-dompdf |
| Excel Export | Maatwebsite/Excel |
| Notifikasi Flash | php-flasher/notyf |

## Instalasi

### Prasyarat

- PHP >= 8.3
- Composer
- Node.js >= 18 & npm
- MySQL

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd libranext
   ```

2. **Jalankan setup otomatis**
   ```bash
   composer run setup
   ```
   Perintah ini akan menjalankan `composer install`, menyalin `.env`, generate app key, menjalankan migrasi, `npm install`, dan build aset secara otomatis.

3. **Konfigurasi database**

   Edit file `.env`:
   ```env
   DB_DATABASE=libranext
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Konfigurasi Midtrans** _(opsional, untuk pembayaran denda online)_
   ```env
   MIDTRANS_SERVER_KEY=
   MIDTRANS_CLIENT_KEY=
   MIDTRANS_IS_PRODUCTION=false
   ```

5. **Seed data awal**
   ```bash
   php artisan db:seed
   ```

6. **Jalankan development server**
   ```bash
   composer run dev
   ```
   Atau jalankan terpisah:
   ```bash
   php artisan serve
   npm run dev
   ```

Akses aplikasi di `http://localhost:8000`.

## Akun Default (setelah seeding)

| Peran | Email | Password |
|---|---|---|
| Admin | admin@libranext.id | password |
| Member | budi@libranext.id | password |
| Member | siti@libranext.id | password |

> Lihat `database/seeders/UserSeeder.php` untuk daftar lengkap akun member.

## Struktur Direktori Penting

```
app/
├── Http/Controllers/
│   ├── Admin/          # Controller khusus admin (laporan, log, kehadiran)
│   ├── Api/            # Webhook Midtrans
│   └── Auth/           # Autentikasi (Breeze)
├── Models/             # User, Book, Category, Borrowing, Fine, Payment, Attendance
database/
├── migrations/         # Skema tabel
├── seeders/            # Data awal (kategori, buku, pengguna, peminjaman)
resources/views/
└── dashboard/
    ├── layouts/        # app, sidebar, topbar, footer
    ├── admin/          # Halaman admin
    └── member/         # Halaman member
routes/
├── web.php             # Semua route web
└── auth.php            # Route autentikasi Breeze
```

## Perintah Artisan Tambahan

```bash
# Kirim notifikasi buku hampir jatuh tempo (terjadwal otomatis setiap hari pukul 08:00)
php artisan notifications:send-due-soon

# Jalankan test
composer run test
```

## Lisensi

MIT
