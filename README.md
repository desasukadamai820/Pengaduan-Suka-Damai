
# Pengaduan Layanan Desa Sukadamai (PHP Native)

## Cara Menjalankan
1. Import `schema.sql` ke MySQL. (Buat database `pengaduan_sukadamai` terlebih dahulu).
2. Edit `config.php` sesuai kredensial MySQL Anda.
3. Jalankan proyek di server PHP/Apache (XAMPP/Laragon). Akses `index.php`.
4. Buat akun lewat `register.php`. Untuk admin, ubah kolom `role` menjadi `admin` pada tabel `users` via phpMyAdmin.

## Fitur
- Registrasi/Login (password_hash).
- Kirim pengaduan privat (kategori, subkategori, lokasi, lampiran).
- Warga: lihat daftar & status pengaduan sendiri.
- Admin: dashboard statistik, daftar pengaduan, ubah status (Baru/Diproses/Selesai/Ditolak).
- Keamanan dasar: prepared statements, validasi sederhana, akses dibatasi.
- Tampilan gelap modern, responsif.

## Struktur Folder
.
├── assets/
│   ├── css/styles.css
│   └── js/app.js
├── uploads/                # lampiran tersimpan di sini
├── partials/
│   ├── header.php
│   └── footer.php
├── index.php
├── login.php
├── register.php
├── logout.php
├── submit.php
├── my_complaints.php
├── view.php
├── update_status.php
├── dashboard.php
├── helpers.php
├── config.php
└── schema.sql

> Catatan: Pengaduan **tidak dipublikasi** ke umum; hanya pelapor & admin yang dapat melihat.

