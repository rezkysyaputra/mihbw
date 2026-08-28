# Panduan Deployment MI Hubbul Wathan

Dokumen ini berisi panduan lengkap untuk melakukan deployment website portal dan panel admin MI Hubbul Wathan ke platform **Vercel (Serverless)** serta alternatif **Shared Hosting / cPanel**.

---

## Opsi 1: Deployment ke Vercel (Rekomendasi)

Vercel adalah platform cloud serverless gratis dan cepat. Proyek ini sudah dikonfigurasi dengan file `vercel.json` dan `api/index.php`.

### 1. Prasyarat Database Online

Karena Vercel bersifat *Serverless / Read-Only Filesystem*, database **tidak bisa menggunakan MySQL localhost**. Anda memerlukan MySQL / PostgreSQL online gratis, contoh:

- **Aiven MySQL / PostgreSQL** (Gratis - aiven.io)
- **Tidb Cloud Serverless MySQL** (Gratis - pingcap.com)
- **Supabase PostgreSQL** / **Neon Database**
- **Railway MySQL**

### 2. Konfigurasi Environment Variables di Vercel

Saat menghubungkan repository GitHub proyek ini ke Vercel (atau di menu *Project Settings > Environment Variables*), tambahkan variabel berikut:

```env
APP_NAME="MI Hubbul Wathan"
APP_ENV=production
APP_KEY=base64:MasukanAppKeyAndaDisini=
APP_DEBUG=false
APP_URL=https://nama-project-anda.vercel.app

DB_CONNECTION=mysql
DB_HOST=host_database_cloud_anda
DB_PORT=3306
DB_DATABASE=nama_db_cloud
DB_USERNAME=user_db_cloud
DB_PASSWORD=password_db_cloud

SESSION_DRIVER=cookie
CACHE_STORE=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

> **Tips `APP_KEY`:** Anda bisa membuat App Key dengan menjalankan `php artisan key:generate --show` di komputer lokal, lalu salin kodenya ke Vercel.

### 3. Build & Deploy

1. Push project Anda ke repository **GitHub / GitLab**.
2. Buka [vercel.com](https://vercel.com) > **Add New Project** > Pilih repository Anda.
3. Pada bagian **Build & Output Settings**:
   - **Framework Preset:** `Other` (atau biarkan default)
   - **Build Command:** `npm run build`
   - **Output Directory:** `public`
4. Klik **Deploy**.

### 4. Menjalankan Migrasi Database

Setelah database cloud terhubung dengan project:

1. Di komputer lokal Anda, arahkan sementara file `.env` ke database cloud tersebut.
2. Jalankan perintah migrasi & data awal:

   ```bash
   php artisan migrate --seed --force
   ```

3. Akun login admin awal:
   - **Email:** `admin@mihubbulwathan.test`
   - **Password:** `password`

---

## Opsi 2: Deployment ke Shared Hosting / cPanel (Alternatif)

Jika ingin memakai hosting konvensional dengan domain sekolah resmi (misal: `.sch.id`):

1. **Build Aset Lokal:**

   ```bash
   npm run build
   ```

2. **Unggah File:**
   - Upload seluruh folder project ke hosting (di luar `public_html`).
   - Pindahkan seluruh isi folder `public/` ke dalam folder `public_html/`.
   - Sesuaikan path pada `public_html/index.php`:

     ```php
     require __DIR__.'/../vendor/autoload.php';
     $app = require_once __DIR__.'/../bootstrap/app.php';
     ```

3. **Database MySQL:**
   - Buat database dan user MySQL melalui menu *cPanel > MySQL Databases*.
   - Impor struktur dan data database dari file `.sql` atau jalankan migrasi via terminal hosting.

4. **Konfigurasi `.env`:**

   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-madrasah.sch.id
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=userhosting_mihbw
   DB_USERNAME=userhosting_root
   DB_PASSWORD=password_db
   ```

5. **Optimasi Cache Laravel (via Terminal cPanel / SSH):**

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan storage:link
   ```

---

## Catatan Tambahan untuk File Unggahan (Storage di Vercel)

Karena Vercel tidak menyimpan file yang diunggah ke server secara permanen (ephemeral filesystem), untuk produksi jangka panjang disarankan mengaktifkan driver cloud storage seperti **AWS S3**, **Cloudflare R2**, atau **Supabase Storage** pada file berkas pendaftaran PPDB.
