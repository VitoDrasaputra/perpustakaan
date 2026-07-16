# Manajemen Data Buku Perpustakaan

Aplikasi web CRUD (Create, Read, Update, Delete) untuk mengelola data buku perpustakaan, dibuat menggunakan **native PHP**, **Bootstrap 5**, dan **MySQL**.

## Fitur
- Menampilkan daftar buku dalam tabel (responsif, bisa discroll di HP)
- Tambah buku baru
- Edit / update data buku
- Hapus buku (dengan konfirmasi)
- Fitur pencarian buku berdasarkan judul/pengarang
- Notifikasi status setelah tambah/edit/hapus data

## Struktur Folder
```
perpustakaan-crud/
├── includes/
│   ├── koneksi.php   -> koneksi ke database
│   ├── header.php    -> navbar & head HTML (Bootstrap)
│   └── footer.php    -> footer & penutup HTML
├── index.php         -> halaman utama (Read + Search)
├── create.php        -> form tambah buku
├── update.php         -> form edit buku
├── delete.php         -> proses hapus buku
└── perpustakaan.sql  -> file database (import ke MySQL)
```

## Cara Menjalankan (Local - XAMPP/Laragon)
1. Copy folder `perpustakaan-crud` ke folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka phpMyAdmin, buat database baru bernama `perpustakaan`.
3. Import file `perpustakaan.sql` ke database tersebut.
4. Sesuaikan koneksi database di `includes/koneksi.php` jika perlu (default: host `localhost`, user `root`, password kosong).
5. Jalankan Apache & MySQL di XAMPP/Laragon.
6. Buka browser, akses: `http://localhost/perpustakaan-crud/index.php`

## Teknologi
- Native PHP (tanpa framework)
- MySQLi untuk koneksi database
- Bootstrap 5 (CDN) untuk tampilan responsif
- Font Awesome untuk ikon

## Catatan Pengembangan
Query pada project ini menggunakan `mysqli_real_escape_string` untuk mencegah SQL Injection dasar. Untuk pengembangan lebih lanjut, bisa ditingkatkan menggunakan **prepared statement** (`mysqli_prepare` / PDO) agar lebih aman.
