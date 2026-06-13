# Deployment Guide for Domainesia

Panduan ini menjelaskan cara menyiapkan project Laravel `Edupays` agar siap dihosting di Domainesia.

## 1. Persiapan sebelum upload

Di komputer lokal:

1. Pastikan project sudah bisa berjalan normal di local.
2. Jalankan build asset frontend:

```bash
npm install
npm run build
```

3. Pastikan `composer.json` sudah sesuai dengan versi PHP di hosting. Project ini membutuhkan PHP `^8.2`.
4. Siapkan database MySQL di Domainesia.

## 2. Setting `.env` production

Ubah file `.env` di server menjadi seperti ini:

```env
APP_NAME=Edupays
APP_ENV=production
APP_KEY=base64:ISI_DARI_KEY_GENERATE
APP_DEBUG=false
APP_URL=https://domain-anda.com

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
```

Catatan:

- `APP_DEBUG` harus `false` di production.
- `APP_URL` harus memakai domain asli, bukan localhost.
- Jika hosting memakai subdomain atau folder, sesuaikan URL-nya.

## 3. Upload file project

Ada dua skenario umum.

### Skenario A: Domainesia mengizinkan document root ke folder `public`

Ini cara yang paling rapi.

1. Upload seluruh isi project ke folder aplikasi, misalnya `/home/user/edupays`.
2. Arahkan document root domain ke folder `public` di dalam project tersebut.
3. Jalankan perintah Laravel di server via SSH:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Skenario B: Domainesia hanya memberi `public_html`

Jika document root tidak bisa diarahkan ke folder `public`, gunakan pendekatan shared hosting berikut.

1. Upload seluruh isi project ke folder lain, misalnya `/home/user/edupays`.
2. Pindahkan isi folder `public` ke `public_html`.
3. Edit file `public_html/index.php`.
4. Ubah path berikut agar mengarah ke folder project utama:

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

Sesuaikan `../` dengan lokasi folder project Anda yang sebenarnya.

Contoh jika project ada di `/home/user/edupays` dan `public_html` di `/home/user/public_html`, maka path harus menunjuk ke folder `edupays/vendor/autoload.php` dan `edupays/bootstrap/app.php`.

## 4. Composer dan vendor

Jika SSH tersedia:

```bash
composer install --no-dev --optimize-autoloader
```

Jika SSH tidak tersedia:

1. Jalankan `composer install --no-dev` di komputer lokal.
2. Upload folder `vendor` bersama project ke server.

## 5. Database dan migrasi

Setelah database dibuat di Domainesia:

1. Import schema jika Anda punya file SQL.
2. Atau jalankan migrasi:

```bash
php artisan migrate --force
```

Jika aplikasi butuh data awal, jalankan seeder:

```bash
php artisan db:seed --force
```

## 6. Storage publik

Karena project Laravel ini memakai folder storage publik, jalankan:

```bash
php artisan storage:link
```

Jika server shared hosting tidak mengizinkan symlink, pastikan file upload diarahkan ke folder yang bisa diakses publik atau sesuaikan konfigurasi upload Anda.

## 7. Permission folder

Pastikan folder berikut bisa ditulis oleh server:

- `storage`
- `bootstrap/cache`

Biasanya permission yang aman di shared hosting adalah `775` atau sesuai kebijakan provider.

## 8. Cache production

Setelah semua beres, kosongkan cache lama lalu cache ulang:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika ada perubahan `.env`, ulangi `php artisan config:cache`.

## 9. Cron job

Kalau project memakai scheduler Laravel, tambahkan cron job di Domainesia:

```bash
* * * * * php /home/user/edupays/artisan schedule:run >> /dev/null 2>&1
```

Sesuaikan path `artisan` dengan lokasi project Anda.

Jika memakai queue, jalankan worker via Supervisor atau cron yang sesuai paket hosting.

## 10. Checklist final

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` sudah benar
- Database MySQL sudah terhubung
- `vendor` tersedia atau `composer install` sudah dijalankan
- `storage:link` sudah dibuat
- Permission `storage` dan `bootstrap/cache` sudah benar
- Asset frontend sudah dibuild dengan `npm run build`
- Cache Laravel sudah dioptimalkan

## 11. Rekomendasi khusus untuk project ini

Project ini menggunakan:

- Laravel 12
- Vite untuk asset frontend
- Database MySQL
- Storage publik Laravel

Artinya, sebelum upload ke hosting, pastikan Anda menjalankan build frontend di lokal karena server shared hosting sering tidak menyediakan Node.js.

## 12. Urutan deployment singkat

1. Jalankan `npm run build` di lokal.
2. Jalankan `composer install --no-dev`.
3. Upload semua file project ke server.
4. Buat database MySQL di Domainesia.
5. Isi `.env` production.
6. Jalankan `php artisan key:generate`.
7. Jalankan `php artisan migrate --force`.
8. Jalankan `php artisan storage:link`.
9. Jalankan `php artisan config:cache`.
10. Tes domain di browser.

## 13. Jika muncul error 500

Cek hal berikut dulu:

- `storage/logs/laravel.log`
- Isi `.env`
- Path `index.php` jika memakai `public_html`
- Versi PHP hosting harus 8.2 atau lebih tinggi
- Permission folder `storage` dan `bootstrap/cache`
