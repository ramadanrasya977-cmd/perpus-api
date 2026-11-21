# LAPORAN PROJECT REST API PERPUSTAKAAN

**Nama Project:** Perpus API  
**Mata Kuliah:** [Isi nama mata kuliah]  
**Nama:** [Isi nama mahasiswa]  
**NIM:** [Isi NIM]  
**Dosen Pengampu:** [Isi nama dosen]  
**Tanggal:** [Isi tanggal pengumpulan]

---

\newpage

## 1. Pendahuluan

Project ini membangun REST API untuk mengelola data buku perpustakaan dan akun pengguna. API menyediakan autentikasi token, operasi CRUD buku, pencarian, pagination, serta dokumentasi interaktif OpenAPI.

## 2. Teknologi yang Digunakan

- Laravel 12 dan PHP 8.3 atau lebih baru.
- SQLite sebagai konfigurasi awal; MySQL dapat digunakan dengan mengubah `.env`.
- Laravel Sanctum untuk personal access token.
- L5-Swagger dan OpenAPI 3 untuk dokumentasi API.
- PHPUnit untuk pengujian otomatis.

## 3. Autentikasi Laravel Sanctum

Pengguna dapat mendaftar dan login menggunakan email serta password. Password disimpan melalui hashing Laravel. Login menghasilkan personal access token Sanctum; token dikirim menggunakan header `Authorization: Bearer <token>`. Endpoint profil dan logout membutuhkan autentikasi. Logout mencabut token yang sedang digunakan.

## 4. CRUD Buku

Data buku berisi judul, penulis, penerbit opsional, ISBN unik, tahun terbit, stok, deskripsi opsional, dan timestamps. Request divalidasi sebelum perubahan disimpan. Daftar buku mendukung pencarian pada judul/penulis dan pagination; ukuran halaman dibatasi maksimum 100 data.

## 5. Daftar Endpoint

| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/api/register` | Registrasi pengguna |
| POST | `/api/login` | Login (throttle 6 percobaan/menit) |
| GET | `/api/me` | Profil pengguna terautentikasi |
| POST | `/api/logout` | Logout dan pencabutan token |
| GET | `/api/books` | Daftar, pencarian, dan pagination buku |
| POST | `/api/books` | Menambah buku |
| GET | `/api/books/{book}` | Melihat detail buku |
| PUT | `/api/books/{book}` | Mengganti seluruh data buku |
| PATCH | `/api/books/{book}` | Mengubah sebagian data buku |
| DELETE | `/api/books/{book}` | Menghapus buku |

## 6. Dokumentasi Hasil Pengujian

Pengujian otomatis dijalankan dengan perintah `php artisan test`. Cakupan pengujian meliputi:

1. Registrasi pengguna berhasil.
2. Login berhasil dan login dengan password salah.
3. Logout mencabut token aktif.
4. Endpoint terlindungi menolak permintaan tanpa token.
5. Daftar buku dan pencarian berdasarkan judul/penulis.
6. Penambahan buku dan validasi ISBN duplikat.
7. Detail, perubahan, dan penghapusan buku.

**Hasil eksekusi terakhir:** 16 pengujian lulus dengan 57 assertion (`php artisan test`). Pengujian juga memastikan endpoint API tetap mengembalikan JSON `401` ketika request tanpa token tidak menyertakan header `Accept: application/json`.

### Bukti screenshot asli

Simpan hasil tangkapan layar dari Swagger UI ke folder `screenshots/`, lalu cantumkan nama file dan keterangan di bawah ini. Tidak ada gambar contoh yang disertakan.

- Screenshot Swagger UI: `screenshots/[nama-file-asli].png`
- Screenshot uji endpoint: `screenshots/[nama-file-asli].png`

## 7. Kesimpulan

Perpus API menyediakan autentikasi token dan CRUD buku yang tervalidasi. Dokumentasi Swagger memudahkan eksplorasi endpoint, sedangkan pengujian PHPUnit membantu memeriksa perilaku API secara otomatis.
