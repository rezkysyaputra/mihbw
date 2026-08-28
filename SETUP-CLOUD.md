# Panduan Setup Cloud Database (TiDB Cloud) & Cloud Storage (Cloudflare R2)

Panduan praktis langkah demi langkah untuk menghubungkan **Database Online Gratis (TiDB Serverless MySQL)** dan **Penyimpanan Gambar Gratis (Cloudflare R2)** agar website MI Hubbul Wathan dapat berjalan 100% di Vercel tanpa kendala upload file dan hilangnya data.

---

## Langkah 1: Setup Database MySQL Cloud Gratis (TiDB Cloud)

TiDB Cloud memberikan database MySQL 8.0 Serverless gratis selamanya hingga 5 GB tanpa perlu memasukkan kartu kredit.

### 1. Buat Akun & Cluster Database
1. Buka [tidbcloud.com](https://tidbcloud.com) lalu daftar menggunakan akun Google/GitHub.
2. Di dashboard, klik **Create Cluster** > Pilih **Serverless** (Free Tier).
3. Pilih Region yang paling dekat: **Singapore (`ap-southeast-1`)**.
4. Beri nama cluster (contoh: `mihbw-db`) lalu klik **Create**.

### 2. Dapatkan Informasi Koneksi
1. Di halaman cluster, klik tombol **Connect**.
2. Di bagian koneksi, pilih tipe **General** atau **MySQL CLI**. Anda akan mendapatkan data:
   - **Host:** `gateway01.ap-southeast-1.prod.aws.tidbcloud.com` (contoh)
   - **Port:** `4000`
   - **User:** `xxxx.root`
   - **Password:** *(Klik Generate Password lalu salin)*
   - **Database:** `test` (Anda bisa membuat database baru bernama `mihbw`)

### 3. Jalankan Migrasi Awal dari Komputer Lokal
Di komputer Anda, buka file `.env`, ubah bagian database mengarah ke TiDB Cloud:
```env
DB_CONNECTION=mysql
DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=mihbw
DB_USERNAME=xxxxxx.root
DB_PASSWORD=password_dari_tidb_cloud
MYSQL_ATTR_SSL_CA=true
```
Lalu jalankan migrasi di terminal komputer Anda:
```bash
php artisan migrate --seed
```

---

## Langkah 2: Setup Penyimpanan Gambar Cloud Gratis (Cloudflare R2)

Cloudflare R2 kompatibel dengan AWS S3 dan memberikan kuota gratis **10 GB storage** dan **0 biaya bandwidth (unlimited download)**.

### 1. Buat Bucket R2
1. Daftar/Login ke [dash.cloudflare.com](https://dash.cloudflare.com).
2. Di menu samping kiri, klik **R2 Object Storage**.
3. Klik **Create Bucket**, beri nama bucket: `mihbw-storage`.
4. Pilih lokasi: **Automatic** (atau Asia Pasifik). Klik **Create Bucket**.

### 2. Aktifkan Domain Publik R2 (Agar Gambar Bisa Dibuka Publik)
1. Buka bucket `mihbw-storage` yang baru dibuat > Buka tab **Settings**.
2. Di bagian **Public Access**, cari opsi **R2.dev subdomain** lalu klik **Enable**.
3. Anda akan mendapatkan URL publik, contoh: `https://pub-abcdef123456789.r2.dev`.

### 3. Buat API Token (Access Key & Secret Key)
1. Kembali ke menu utama **R2** di Cloudflare > Di sebelah kanan, klik **Manage R2 API Tokens**.
2. Klik **Create API Token**.
3. Pilih Permissions: **Object Read & Write**.
4. Klik **Create API Token**. Salin nilai berikut:
   - **Access Key ID**
   - **Secret Access Key**
   - **Endpoint URL:** *(Format: `https://<ACCOUNT_ID>.r2.cloudflarestorage.com`)*

---

## Langkah 3: Tambahkan Driver S3 di Laravel

Di terminal komputer Anda, jalankan perintah berikut untuk mengaktifkan driver S3/R2 di Laravel:

```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

---

## Langkah 4: Masukkan Semua Variabel ke Dashboard Vercel

Buka proyek Anda di **[Vercel Dashboard](https://vercel.com) > Project Settings > Environment Variables**, lalu tambahkan semua variabel berikut:

```env
APP_NAME="MI Hubbul Wathan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nama-project-anda.vercel.app

# Database TiDB Cloud (MySQL)
DB_CONNECTION=mysql
DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=mihbw
DB_USERNAME=xxxxxx.root
DB_PASSWORD=password_dari_tidb_cloud

# Storage Cloudflare R2 (S3 Compatible)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=access_key_dari_cloudflare
AWS_SECRET_ACCESS_KEY=secret_key_dari_cloudflare
AWS_DEFAULT_REGION=auto
AWS_BUCKET=mihbw-storage
AWS_ENDPOINT=https://account_id.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_URL=https://pub-abcdef123456789.r2.dev

# Sesi & Cache Serverless
SESSION_DRIVER=cookie
CACHE_STORE=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

Setelah semua variabel di atas tersimpan di Vercel, lakukan **Redeploy**. Website madrasah Anda kini sudah online penuh dengan database persisten dan sistem upload gambar cloud gratis tanpa batas waktu.
