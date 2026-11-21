# Perpus API

REST API perpustakaan dengan Laravel 12, PHP 8.3+, SQLite/MySQL, Laravel Sanctum, dan dokumentasi OpenAPI 3 melalui L5-Swagger.

## Persyaratan

- PHP 8.3 atau lebih baru dengan ekstensi `pdo_sqlite` (untuk SQLite) atau `pdo_mysql` (untuk MySQL).
- Composer 2.
- Laravel 12.

Pada Windows/Laragon, pilih PHP 8.3+ untuk terminal, pastikan `php` dan `composer` tersedia di PATH, lalu aktifkan ekstensi `pdo_sqlite` dan `sqlite3` pada `php.ini` aktif (periksa lokasinya dengan `php --ini`).

## Instalasi dan konfigurasi

Jalankan dari PowerShell pada folder project:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File -Path database\database.sqlite -Force
```

Pastikan `.env` menggunakan `DB_CONNECTION=sqlite`. Untuk MySQL, atur `DB_CONNECTION=mysql` serta `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai lingkungan lokal. Jangan pernah mengunggah `.env`.

Sanctum sudah dipasang dengan `php artisan install:api`; `routes/api.php` berisi route API project dan migrasi token Sanctum tersedia. L5-Swagger sudah terpasang dan konfigurasinya tersedia di `config/l5-swagger.php`.

Jalankan migrasi dan isi data demo:

```powershell
php artisan migrate --seed
php artisan l5-swagger:generate
```

Seeder membuat akun demo `admin@example.com` dengan password `password`, dan memastikan tersedia 20 buku. Gunakan hanya untuk pengembangan lokal; ganti kredensial sebelum deployment.

## Menjalankan aplikasi

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Server lokal: <http://localhost:8000>. Jalankan pengujian dengan `php artisan test`.

## Autentikasi

Daftar melalui `POST /api/register` atau login melalui `POST /api/login`. Respons login dan registrasi mengembalikan token Sanctum. Kirim token pada header berikut untuk endpoint terlindungi:

```text
Authorization: Bearer <token>
Accept: application/json
```

## Endpoint

| Method | Endpoint | Auth | Fungsi |
|---|---|---:|---|
| POST | `/api/register` | Tidak | Registrasi dan penerbitan token |
| POST | `/api/login` | Tidak (maks. 6 percobaan/menit) | Login dan penerbitan token |
| GET | `/api/me` | Ya | Profil pengguna aktif |
| POST | `/api/logout` | Ya | Mencabut token aktif |
| GET | `/api/books` | Ya | Daftar buku, pencarian, dan pagination |
| POST | `/api/books` | Ya | Menambahkan buku |
| GET | `/api/books/{book}` | Ya | Detail buku |
| PUT | `/api/books/{book}` | Ya | Mengganti seluruh data buku |
| PATCH | `/api/books/{book}` | Ya | Memperbarui sebagian data buku |
| DELETE | `/api/books/{book}` | Ya | Menghapus buku |

Pencarian daftar buku menggunakan `?search=kata` untuk judul atau penulis. Pagination menggunakan `?page=1&per_page=15` (`per_page` maksimum 100). ISBN harus unik; `published_year` tidak boleh melebihi tahun berjalan dan `stock` harus nol atau lebih.

## Swagger / OpenAPI

Generate spesifikasi setelah instalasi atau perubahan dokumentasi:

```powershell
php artisan l5-swagger:generate
```

Buka <http://localhost:8000/api/documentation>. Klik **Authorize**, login menggunakan akun demo melalui endpoint `POST /api/login`, lalu masukkan token pada field bearer.

## Struktur dokumentasi dan screenshot

- `docs/LAPORAN-API.md` — template laporan yang dapat diekspor ke PDF.
- `screenshots/` — simpan screenshot asli Swagger UI atau hasil uji pada folder ini; tidak ada screenshot contoh/palsu di repository.

## GitHub

File `.env`, database SQLite lokal, token, dan hasil generasi spesifikasi dikecualikan oleh `.gitignore`. Sebelum push, periksa `git status` dan pastikan tidak ada file rahasia. Tambahkan URL repository remote hanya setelah repository GitHub dibuat.
