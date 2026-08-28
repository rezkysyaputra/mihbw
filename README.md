# MI Hubbul Wathan Portal

Portal sekolah MI Hubbul Wathan berbasis Laravel 12, Filament 4, Blade, Tailwind CSS, dan SQLite/MySQL sesuai kebutuhan lingkungan deploy.

## Dokumen

- [Panduan penggunaan web](PANDUAN-WEB.md)
- [Panduan deployment shared hosting](DEPLOYMENT.md)

## Ringkasan Fitur

- Website publik untuk profil sekolah, berita, pengumuman, galeri, guru, agenda, unduhan, dan PPDB.
- Panel admin di `/admin`.
- Role akses `Admin` dan `Guru`.
- Dashboard admin dengan analitik dan log aktivitas.
- Upload gambar publik, galeri, berita, guru, dan dokumen sekolah.

## Akses Awal

- Admin: `admin@mihubbulwathan.test`
- Guru: `guru@mihubbulwathan.test`
- Password awal: `password`

## Catatan Operasional

- Gambar publik memakai folder `storage` yang ditautkan ke web root.
- Jika hosting tidak mendukung symlink, gunakan folder publik khusus sesuai panduan deploy.
- Setelah login pertama, ganti password akun awal.
