# Shoe Laundry

> Aplikasi web untuk operasional laundry sepatu: pencatatan pesanan, tracking pesanan pelanggan, manajemen layanan, voucher, artikel, karyawan, export laporan, dan integrasi pembayaran.

![Status](https://img.shields.io/badge/status-active%20development-blue)
![Laravel](https://img.shields.io/badge/Laravel-13.x-red)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC)
![License](https://img.shields.io/badge/license-MIT-green)

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Prerequisites](#prerequisites)
- [Installation & Setup](#installation--setup)
- [Environment Variables](#environment-variables)
- [Running the Project](#running-the-project)
- [API Reference](#api-reference)
- [Database Schema](#database-schema)
- [Deployment Guide](#deployment-guide)
- [Testing](#testing)
- [Branching Strategy](#branching-strategy)
- [Commit Convention](#commit-convention)
- [Contribution Guide](#contribution-guide)
- [Pull Request & Code Review](#pull-request--code-review)
- [Coding Standards](#coding-standards)
- [Troubleshooting & FAQ](#troubleshooting--faq)
- [Changelog](#changelog)
- [License](#license)

## Overview

**Shoe Laundry** adalah aplikasi operasional untuk bisnis laundry sepatu. Aplikasi ini membantu owner atau admin mengelola pesanan pelanggan, layanan, add-on, voucher, artikel, karyawan, dan laporan order. Pelanggan dapat melihat halaman publik dan melakukan tracking pesanan menggunakan nomor order serta empat digit terakhir nomor telepon.

| Item | Keterangan |
| --- | --- |
| Nama Project | Shoe Laundry |
| Tipe Project | Web App + JSON API |
| Status Project | Active Development |
| Target Pengguna | Owner laundry sepatu, admin internal, dan pelanggan untuk tracking pesanan |
| Bahasa Utama | PHP, JavaScript, Blade |
| Framework Utama | Laravel |
| Database Default | SQLite untuk lokal, dapat diganti ke MySQL/PostgreSQL sesuai konfigurasi Laravel |

Tujuan utama project:

- Memusatkan data order dan status pengerjaan sepatu.
- Memudahkan staff membuat pesanan dan owner memantau laporan.
- Memberikan tracking order sederhana untuk pelanggan.
- Menyediakan API untuk integrasi mobile app atau dashboard lain.
- Mendukung pembayaran non-tunai melalui Xendit invoice.

## Features

### 🔐 Authentication & Authorization

- Login menggunakan Laravel Breeze.
- Register publik dinonaktifkan; akun staff/karyawan dibuat dari panel owner.
- Role utama:
  - `owner`: akses dashboard admin, laporan, layanan, voucher, karyawan, artikel.
  - `karyawan`: akun seed tersedia, tetapi login web/API untuk role ini sedang dibatasi pada beberapa alur.
  - `customer`: role default pada tabel `users` jika diperlukan untuk pengembangan berikutnya.
- API menggunakan Laravel Sanctum personal access token.

### 📦 Order Management

- CRUD pesanan melalui panel admin dan API.
- Pembuatan pesanan multi-item dengan satu `order_number` bersama.
- Auto-generate:
  - `order_number`: format tahun dua digit + 5 karakter acak, contoh `26A1B2C`.
  - `tracking_code`: 5 karakter acak.
- Data order mencakup:
  - nama pelanggan,
  - nomor telepon,
  - brand sepatu,
  - ukuran,
  - kondisi sepatu,
  - layanan,
  - add-on,
  - biaya tambahan,
  - voucher,
  - total harga,
  - metode dan status pembayaran,
  - status pengerjaan,
  - catatan.
- Status pengerjaan yang digunakan model:

```text
Waiting -> Cleaning -> Drying -> Ready -> Delivered
cancelled
```

### 🔎 Public Order Tracking

- Endpoint publik `/track`.
- Pelanggan memasukkan nomor order/tracking dengan prefix opsional `KC-`.
- Validasi tambahan menggunakan 4 digit terakhir nomor telepon.
- Jika nomor telepon tidak cocok, hasil tracking tidak ditampilkan.

### 🧾 Service & Add-On Management

- CRUD layanan oleh owner.
- Kategori layanan:
  - `Special Treatment`
  - `Cleaning`
  - `Repair Treatment`
  - `Repaint Treatment`
- Setiap layanan memiliki nama, kategori, deskripsi, harga, dan estimasi hari.
- Add-on digunakan untuk biaya tambahan seperti `For White`, `Extra Hard`, `Waxing`, dan lainnya.

### 🎟️ Voucher Management

- CRUD voucher dari web admin dan API.
- Tipe diskon:
  - `fixed`: potongan nominal.
  - `percent`: potongan persentase.
- Aturan voucher:
  - minimum order,
  - maksimal penggunaan,
  - jumlah penggunaan,
  - tanggal mulai,
  - tanggal berakhir,
  - status aktif/nonaktif.
- Validasi voucher tersedia melalui endpoint check.

### 📊 Dashboard & Export Laporan

- Dashboard owner menampilkan ringkasan artikel.
- API dashboard menampilkan statistik order:
  - total order,
  - order berdasarkan status,
  - order belum lunas,
  - order terbaru.
- Export order ke Excel menggunakan `maatwebsite/excel`.
- Filter export mendukung periode dan rentang tanggal.

### 📰 Article / Blog Management

- Halaman publik menampilkan artikel yang sudah dipublish.
- CRUD artikel di admin panel.
- Upload gambar artikel ke disk `public`.
- Konten artikel mendukung format JSON editor-style dan fallback HTML/plain text.

### 💳 Payment Integration

- Integrasi Xendit invoice melalui package `xendit/xendit-php`.
- Metode pembayaran order:
  - `Cash`
  - `QRIS`
  - `Transfer Bank`
- Untuk order non-cash tunggal, aplikasi dapat membuat invoice Xendit dan mengarahkan user ke invoice URL.
- Payment webhook method sudah tersedia pada `PaymentController`, tetapi route webhook belum terlihat di `routes/web.php` atau `routes/api.php`.

```text
[TODO: pastikan route webhook Xendit didaftarkan sebelum production]
```

## Tech Stack

| Teknologi | Versi | Fungsi | Alasan Pemilihan |
| --- | --- | --- | --- |
| PHP | `^8.3` | Runtime backend | Modern, stabil, sesuai kebutuhan Laravel terbaru |
| Laravel Framework | `^13.0` | Backend MVC, routing, auth, ORM | Struktur konvensional, mudah dimaintain banyak tim |
| Laravel Breeze | `^2.4` | Auth scaffolding | Login/profile dasar cepat dan mengikuti pola Laravel |
| Laravel Sanctum | `^4.0` | API token auth | Cocok untuk API internal/mobile dengan personal access token |
| Laravel Tinker | `^3.0` | REPL/debugging | Memudahkan inspeksi data dan model |
| Maatwebsite Excel | `^3.1` | Export laporan Excel | Standar umum untuk export spreadsheet di Laravel |
| Xendit PHP SDK | `2.14.0` | Payment invoice | Integrasi pembayaran Indonesia |
| PHPUnit | `^12.5.12` | Automated test | Test runner bawaan ekosistem Laravel |
| Laravel Pint | `^1.27` | Code style PHP | Formatting standar Laravel |
| Vite | `^8.0.0` | Frontend build tool | Build asset cepat untuk Laravel |
| Tailwind CSS | `^3.4.19` | Utility-first CSS | Mempercepat styling Blade |
| Alpine.js | `^3.4.2` | Interaksi ringan di Blade | Cocok untuk UI sederhana tanpa SPA penuh |
| Axios | `^1.15.2` | HTTP client frontend | Digunakan untuk request AJAX bila diperlukan |
| Concurrently | `^9.0.1` | Menjalankan banyak command dev | Dipakai di script `composer dev` |

## Project Structure

Struktur folder utama:

```text
shoe-laundry/
├── app/
│   ├── Exports/
│   │   └── OrdersExport.php
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Providers/
│   └── View/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── images/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   ├── api.php
│   ├── auth.php
│   ├── console.php
│   └── web.php
├── scraper/
├── storage/
├── tests/
├── composer.json
├── package.json
├── phpunit.xml
├── tailwind.config.js
└── vite.config.js
```

Penjelasan folder/file penting:

| Path | Fungsi |
| --- | --- |
| `app/Http/Controllers/AdminController.php` | Controller utama untuk dashboard admin, order web, layanan, add-on, karyawan, voucher, dan export |
| `app/Http/Controllers/ApiController.php` | Login API, service API, add-on API, dashboard API |
| `app/Http/Controllers/OrderController.php` | CRUD order JSON dan store/destroy dari web admin |
| `app/Http/Controllers/TrackingController.php` | Halaman tracking publik |
| `app/Http/Controllers/PaymentController.php` | Pembuatan invoice dan webhook Xendit |
| `app/Http/Controllers/ArticleController.php` | Artikel publik dan admin artikel |
| `app/Http/Middleware/IsAdmin.php` | Middleware pembatas akses admin |
| `app/Http/Middleware/RestrictDirectAccess.php` | Middleware pembatas akses langsung pada route tertentu |
| `app/Models/Order.php` | Model order, generator nomor order/tracking, status, deadline helper |
| `app/Models/Service.php` | Model layanan laundry |
| `app/Models/AddOn.php` | Model biaya tambahan |
| `app/Models/Voucher.php` | Model voucher dan casting field voucher |
| `app/Models/Article.php` | Model artikel dan helper excerpt |
| `app/Exports/OrdersExport.php` | Export laporan order ke Excel |
| `routes/web.php` | Route halaman publik dan panel admin |
| `routes/api.php` | Route JSON API dengan Sanctum |
| `routes/auth.php` | Route auth dari Laravel Breeze |
| `database/migrations/` | Definisi schema database |
| `database/seeders/DatabaseSeeder.php` | Seeder owner, karyawan, layanan, dan sample order |
| `resources/views/admin/` | Blade view untuk dashboard admin |
| `resources/views/articles/` | Blade view artikel publik |
| `resources/views/auth/` | Blade view authentication |
| `resources/views/tracking.blade.php` | Halaman tracking order pelanggan |
| `public/images/` | Asset gambar publik |
| `.env.example` | Template environment variable |

## Prerequisites

Pastikan environment lokal memiliki:

- PHP `8.3` atau lebih baru.
- Composer `2.x`.
- Node.js `[TODO: isi versi standar tim, rekomendasi Node LTS]`.
- npm `10.x` atau sesuai versi Node yang dipakai.
- SQLite untuk setup lokal default, atau MySQL/PostgreSQL jika mengganti `DB_CONNECTION`.
- Git.
- Ekstensi PHP umum Laravel:
  - `ctype`
  - `curl`
  - `dom`
  - `fileinfo`
  - `filter`
  - `hash`
  - `mbstring`
  - `openssl`
  - `pdo`
  - `pdo_sqlite` atau driver database lain
  - `tokenizer`
  - `xml`

Cek versi:

```bash
php -v
composer -V
node -v
npm -v
```

## Installation & Setup

### 1. Clone Repository

```bash
git clone [TODO: isi URL repository]
cd shoe-laundry
```

### 2. Install Dependency Backend

```bash
composer install
```

### 3. Install Dependency Frontend

```bash
npm install
```

### 4. Buat File Environment

```bash
cp .env.example .env
```

Di Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Setup Database

Default `.env.example` menggunakan SQLite:

```env
DB_CONNECTION=sqlite
```

Buat file SQLite jika belum ada:

```bash
touch database/database.sqlite
```

Di Windows PowerShell:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

Jika memakai MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shoe_laundry
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan Migration dan Seeder

```bash
php artisan migrate --seed
```

Akun awal dari seeder:

```text
Owner:
Email    : owner@laundry.com
Password : password
Role     : owner

Karyawan:
Email    : karyawan@laundry.com
Password : password
Role     : karyawan
```

Catatan: pada kode saat ini login karyawan dinonaktifkan di beberapa flow. Gunakan akun owner untuk akses panel admin.

### 8. Buat Storage Link

Diperlukan untuk menampilkan gambar artikel yang tersimpan di `storage/app/public`.

```bash
php artisan storage:link
```

### 9. Build Asset Frontend

Untuk development:

```bash
npm run dev
```

Untuk production build:

```bash
npm run build
```

### 10. Jalankan Server Lokal

```bash
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

Alternatif satu command development dari `composer.json`:

```bash
composer dev
```

Command ini menjalankan server Laravel, queue listener, log pail, dan Vite secara bersamaan.

## Environment Variables

Environment utama dari `.env.example`:

| Variable | Wajib | Default | Penjelasan |
| --- | --- | --- | --- |
| `APP_NAME` | Ya | `Laravel` | Nama aplikasi. Rekomendasi: `Shoe Laundry` |
| `APP_ENV` | Ya | `local` | Environment aplikasi: `local`, `staging`, `production` |
| `APP_KEY` | Ya | kosong | Key enkripsi Laravel, dibuat dengan `php artisan key:generate` |
| `APP_DEBUG` | Ya | `true` | Debug mode. Wajib `false` di production |
| `APP_URL` | Ya | `http://localhost` | Base URL aplikasi |
| `APP_LOCALE` | Tidak | `en` | Locale aplikasi |
| `APP_FALLBACK_LOCALE` | Tidak | `en` | Fallback locale |
| `APP_FAKER_LOCALE` | Tidak | `en_US` | Locale data palsu untuk factory |
| `LOG_CHANNEL` | Ya | `stack` | Channel logging Laravel |
| `LOG_STACK` | Tidak | `single` | Stack logger yang dipakai |
| `LOG_LEVEL` | Ya | `debug` | Level logging |
| `DB_CONNECTION` | Ya | `sqlite` | Driver database |
| `DB_HOST` | Jika non-SQLite | `127.0.0.1` | Host database |
| `DB_PORT` | Jika non-SQLite | `3306` | Port database |
| `DB_DATABASE` | Jika non-SQLite | `laravel` | Nama database atau path SQLite |
| `DB_USERNAME` | Jika non-SQLite | `root` | Username database |
| `DB_PASSWORD` | Jika non-SQLite | kosong | Password database |
| `SESSION_DRIVER` | Ya | `database` | Penyimpanan session |
| `SESSION_LIFETIME` | Ya | `120` | Masa aktif session dalam menit |
| `CACHE_STORE` | Ya | `database` | Penyimpanan cache |
| `QUEUE_CONNECTION` | Ya | `database` | Driver queue |
| `FILESYSTEM_DISK` | Ya | `local` | Disk default file upload |
| `MAIL_MAILER` | Ya | `log` | Driver email |
| `MAIL_HOST` | Jika SMTP | `127.0.0.1` | Host SMTP |
| `MAIL_PORT` | Jika SMTP | `2525` | Port SMTP |
| `MAIL_USERNAME` | Jika SMTP | `null` | Username SMTP |
| `MAIL_PASSWORD` | Jika SMTP | `null` | Password SMTP |
| `MAIL_FROM_ADDRESS` | Ya | `hello@example.com` | Alamat pengirim email |
| `MAIL_FROM_NAME` | Ya | `${APP_NAME}` | Nama pengirim email |
| `VITE_APP_NAME` | Ya | `${APP_NAME}` | Nama aplikasi untuk frontend |
| `XENDIT_SECRET_KEY` | Jika Xendit aktif | `[TODO]` | Secret key Xendit, dibaca oleh `config/xendit.php` |
| `XENDIT_WEBHOOK_VERIFICATION_TOKEN` | Jika Xendit aktif | `[TODO]` | Token verifikasi webhook Xendit |

Contoh `.env` lokal:

```env
APP_NAME="Shoe Laundry"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log

XENDIT_SECRET_KEY=
XENDIT_WEBHOOK_VERIFICATION_TOKEN=
```

Catatan maintenance:

```text
[TODO: tambahkan XENDIT_SECRET_KEY dan XENDIT_WEBHOOK_VERIFICATION_TOKEN ke .env.example jika payment dipakai di semua environment]
```

## Running the Project

### Development

Opsi 1, jalankan proses terpisah:

```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1 --timeout=0
```

Opsi 2, gunakan script Composer:

```bash
composer dev
```

### Staging

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:work --tries=3
```

Gunakan `.env` staging:

```env
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.example.com
```

### Production

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:work --tries=3 --timeout=90
```

Gunakan `.env` production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://[TODO: domain-production]
LOG_LEVEL=warning
```

## API Reference

Base URL lokal:

```text
http://127.0.0.1:8000/api
```

Header untuk endpoint terproteksi:

```http
Authorization: Bearer <token>
Accept: application/json
Content-Type: application/json
```

### Auth

#### POST `/api/login`

Login API dan membuat Sanctum token.

Request body:

```json
{
  "email": "owner@laundry.com",
  "password": "password",
  "device_name": "mobile_app"
}
```

Response sukses:

```json
{
  "token": "1|plain-text-token",
  "user": {
    "id": 1,
    "name": "Owner Laundry",
    "email": "owner@laundry.com",
    "role": "owner"
  }
}
```

Response error:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["Kredensial yang diberikan tidak cocok."]
  }
}
```

Catatan: role `karyawan` akan mendapat response `403` pada API login.

#### POST `/api/logout`

Logout dan menghapus token aktif.

Response sukses:

```json
{
  "message": "Logged out successfully"
}
```

#### GET `/api/user`

Mengambil user yang sedang login.

Response sukses:

```json
{
  "id": 1,
  "name": "Owner Laundry",
  "email": "owner@laundry.com",
  "role": "owner"
}
```

### Dashboard

#### GET `/api/dashboard`

Mengambil statistik ringkas order.

Response sukses:

```json
{
  "total_orders": 10,
  "waiting_orders": 3,
  "cleaning_orders": 2,
  "drying_orders": 1,
  "ready_orders": 2,
  "delivered_orders": 2,
  "unpaid_orders": 4,
  "recent_orders": []
}
```

### Services

#### GET `/api/services`

Mengambil semua layanan.

Response sukses:

```json
[
  {
    "id": 1,
    "category": "Cleaning",
    "name": "Deep Clean Three Days",
    "description": "Deep cleaning selesai dalam 3 hari.",
    "price": 42500,
    "estimated_days": "3"
  }
]
```

#### POST `/api/services`

Membuat layanan baru.

Request body:

```json
{
  "category": "Cleaning",
  "name": "Regular Clean",
  "price": 25000,
  "estimated_days": "4 hari"
}
```

#### PUT `/api/services/{id}`

Mengubah layanan.

Request body:

```json
{
  "name": "Deep Clean Express",
  "price": 55000
}
```

#### DELETE `/api/services/{id}`

Menghapus layanan.

Response sukses:

```json
{
  "success": true,
  "deleted": 1
}
```

### Add-Ons

#### GET `/api/add_ons`

Mengambil semua add-on.

#### POST `/api/add_ons`

Request body:

```json
{
  "name": "For White",
  "price": 5000
}
```

#### DELETE `/api/add_ons/{id}`

Response sukses:

```json
{
  "success": true,
  "deleted": 1
}
```

### Vouchers

#### GET `/api/vouchers`

Mengambil semua voucher, urut terbaru.

#### POST `/api/vouchers`

Request body:

```json
{
  "code": "NEWYEAR26",
  "discount_amount": 10,
  "discount_type": "percent",
  "min_order": 50000,
  "max_uses": 100,
  "valid_from": "2026-01-01",
  "valid_until": "2026-01-31"
}
```

#### PUT `/api/vouchers/{id}`

Mengubah voucher.

#### DELETE `/api/vouchers/{id}`

Menghapus voucher.

#### POST `/api/vouchers/{id}/toggle`

Mengaktifkan atau menonaktifkan voucher.

Response sukses:

```json
{
  "message": "Status voucher berhasil diubah",
  "is_active": false
}
```

#### POST `/api/vouchers/check`

Validasi voucher terhadap subtotal.

Request body:

```json
{
  "code": "NEWYEAR26",
  "subtotal": 100000
}
```

Response sukses:

```json
{
  "message": "Voucher valid",
  "data": {
    "code": "NEWYEAR26",
    "discount_amount": 10,
    "discount_type": "percent"
  }
}
```

Response error:

```json
{
  "message": "Voucher sudah kadaluarsa"
}
```

### Orders

#### GET `/api/orders`

Mengambil daftar order dengan pagination.

Query parameter:

| Parameter | Tipe | Keterangan |
| --- | --- | --- |
| `search` | string | Cari berdasarkan nomor order, nama customer, telepon, atau pembuat |
| `status` | string | Filter status order |
| `start_date` | date | Filter tanggal mulai |
| `end_date` | date | Filter tanggal akhir |
| `per_page` | integer | Jumlah data per halaman |
| `all` | boolean | Jika `true` dan user `owner`, ambil maksimal 500 data |

Contoh:

```http
GET /api/orders?search=Budi&status=Waiting&per_page=20
```

#### GET `/api/orders/{id}`

Mengambil detail order berdasarkan `order_id`.

Response error:

```json
{
  "message": "Pesanan tidak ditemukan"
}
```

#### POST `/api/orders`

Membuat order baru. Field `items` wajib minimal satu item.

Request body:

```json
{
  "customer_name": "Budi Santoso",
  "phone_number": "081234567890",
  "payment_method": "Cash",
  "payment_status": "unpaid",
  "voucher_code": null,
  "notes": "Ada noda tinta di toe box.",
  "items": [
    {
      "shoe_brand": "Nike Air Max",
      "shoe_size": "42",
      "shoe_condition": "Kotor Sedang",
      "service_name": "Deep Clean Three Days",
      "additional_fees": 0,
      "total_price": 42500,
      "estimated_days": "3 hari",
      "add_ons": [
        {
          "name": "For White",
          "price": 5000
        }
      ]
    }
  ]
}
```

Response sukses:

```json
{
  "success": true,
  "order_number": "26A1B2C",
  "message": "1 pesanan berhasil dibuat!"
}
```

#### PUT `/api/orders/{id}`

Mengubah order.

Request body:

```json
{
  "status": "Cleaning",
  "payment_status": "paid",
  "notes": "Sudah masuk tahap cleaning."
}
```

Response sukses:

```json
{
  "success": true,
  "message": "Pesanan berhasil diperbarui",
  "order": {}
}
```

#### DELETE `/api/orders/{id}`

Menghapus order.

Response sukses:

```json
{
  "success": true,
  "message": "Pesanan berhasil dihapus"
}
```

### Employees

#### GET `/api/employees`

Mengambil user dengan role selain `customer`.

#### POST `/api/employees`

Request body:

```json
{
  "name": "Admin Outlet",
  "email": "admin@example.com",
  "whatsapp": "081234567890",
  "password": "password",
  "role": "owner"
}
```

Valid role pada API employee saat ini:

```text
admin, staff, owner
```

Catatan: panel web menggunakan role `karyawan`, sedangkan API employee menggunakan `admin`, `staff`, `owner`. Samakan konvensi role sebelum integrasi jangka panjang.

#### PUT `/api/employees/{id}`

Mengubah data employee.

#### DELETE `/api/employees/{id}`

Menghapus employee. User tidak dapat menghapus dirinya sendiri.

### Web Routes Penting

| Method | Path | Nama Route | Keterangan |
| --- | --- | --- | --- |
| `GET` | `/` | `home` | Landing/home publik dengan artikel dan layanan |
| `GET` | `/articles` | `articles.index` | Daftar artikel publik |
| `GET` | `/articles/{slug}` | `articles.show` | Detail artikel publik |
| `GET` | `/track` | `track` | Form tracking pesanan |
| `POST` | `/track` | `track.search` | Pencarian tracking |
| `GET` | `/login` | `login` | Login web |
| `POST` | `/logout` | `logout` | Logout web |
| `GET` | `/admin/dashboard` | `admin.dashboard` | Dashboard owner |
| `GET` | `/admin/orders` | `admin.orders` | Daftar order admin |
| `POST` | `/admin/orders` | `orders.store` | Membuat order |
| `GET` | `/admin/orders/export` | `admin.orders.export` | Export order ke Excel |
| `GET` | `/admin/services` | `admin.services` | Kelola layanan dan add-on |
| `GET` | `/admin/employees` | `admin.employees` | Kelola karyawan |
| `GET` | `/admin/vouchers` | `admin.vouchers` | Kelola voucher |
| `GET` | `/admin/articles` | `admin.articles` | Kelola artikel |
| `GET` | `/payment/pay/{order}` | `payment.pay` | Buat invoice pembayaran |

## Database Schema

### Tabel Utama

#### `users`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `name` | string | Nama user |
| `email` | string unique | Email login |
| `email_verified_at` | timestamp nullable | Waktu verifikasi email |
| `whatsapp` | string nullable | Nomor WhatsApp, ditambahkan migration |
| `password` | string | Password hashed |
| `role` | string | Role user, default `customer` |
| `remember_token` | string nullable | Token remember me |
| `created_at`, `updated_at` | timestamp | Audit timestamp |

#### `orders`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `order_id` | bigint | Primary key |
| `order_number` | string nullable | Nomor order bersama, format contoh `26A1B2C` |
| `tracking_code` | string nullable | Kode tracking 5 karakter |
| `customer_name` | string | Nama pelanggan |
| `phone_number` | string | Nomor telepon pelanggan |
| `shoe_brand` | string nullable | Brand/model sepatu |
| `shoe_size` | string nullable | Ukuran sepatu |
| `shoe_condition` | string nullable | Kondisi sepatu |
| `service_category` | string nullable | Kategori layanan |
| `service_name` | string | Nama layanan |
| `add_ons` | text nullable | JSON add-on |
| `additional_fees` | integer | Biaya tambahan |
| `total_price` | integer | Total harga setelah diskon |
| `estimated_days` | string nullable | Estimasi pengerjaan |
| `payment_method` | string nullable | `Cash`, `QRIS`, `Transfer Bank` |
| `payment_status` | string | `unpaid`, `paid`, kemungkinan `expired` dari webhook |
| `status` | string | Status pengerjaan |
| `notes` | text nullable | Catatan order |
| `created_by` | string nullable | Nama user pembuat order |
| `external_id` | string nullable | Referensi invoice Xendit |
| `voucher_code` | string nullable | Kode voucher |
| `discount_amount` | integer | Nominal diskon |
| `created_at`, `updated_at` | timestamp | Audit timestamp |

Index penting pada `orders`:

```text
customer_name
phone_number
status
payment_status
created_at
```

#### `services`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `category` | string | Kategori layanan |
| `name` | string | Nama layanan |
| `description` | text nullable | Deskripsi layanan |
| `price` | integer | Harga layanan |
| `estimated_days` | string nullable | Estimasi pengerjaan |
| `created_at`, `updated_at` | timestamp | Audit timestamp |

#### `add_ons`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `name` | string | Nama add-on |
| `price` | integer | Harga add-on |
| `created_at`, `updated_at` | timestamp | Audit timestamp |

#### `vouchers`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `code` | string unique | Kode voucher |
| `discount_amount` | integer/decimal | Nilai diskon |
| `discount_type` | enum | `fixed` atau `percent` |
| `min_order` | decimal | Minimum subtotal |
| `max_uses` | integer | Batas penggunaan |
| `used_count` | integer | Jumlah penggunaan |
| `valid_from` | date nullable | Tanggal mulai berlaku |
| `valid_until` | date nullable | Tanggal selesai berlaku |
| `is_active` | boolean | Status voucher |
| `created_at`, `updated_at` | timestamp | Audit timestamp |

#### `articles`

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `title` | string | Judul artikel |
| `slug` | string unique | Slug URL artikel |
| `content` | text | Konten artikel |
| `image` | string nullable | Path gambar utama |
| `is_published` | boolean | Status publish |
| `created_at`, `updated_at` | timestamp | Audit timestamp |

#### Tabel Laravel Bawaan

- `password_reset_tokens`
- `sessions`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`
- `personal_access_tokens`

### Relasi Data

Relasi saat ini sebagian besar bersifat denormalisasi:

- `orders.service_name` menyimpan nama layanan, bukan `service_id`.
- `orders.voucher_code` menyimpan kode voucher, bukan `voucher_id`.
- `orders.created_by` menyimpan nama pembuat, bukan `user_id`.
- `users.orders()` ada di model, tetapi tabel `orders` saat ini tidak memakai `user_id`.

Hal ini memudahkan snapshot data historis, tetapi perlu perhatian saat membuat laporan relasional.

### ERD Sederhana

```text
+---------+          +------------------------+
| users   |          | personal_access_tokens |
|---------|          |------------------------|
| id PK   |<-------->| tokenable_id           |
| name    |          | tokenable_type         |
| email   |          | token                  |
| role    |          +------------------------+
+---------+

+----------+       references by text       +----------+
| services |<-------------------------------| orders   |
|----------|                                |----------|
| id PK    |                                | order_id PK
| category |                                | order_number
| name     |                                | service_name
| price    |                                | voucher_code
+----------+                                | add_ons JSON
                                            | total_price
+----------+       references by text       | status
| vouchers |<-------------------------------| payment_status
|----------|                                +----------+
| id PK    |
| code     |
| type     |
| active   |
+----------+

+----------+       embedded JSON in orders.add_ons
| add_ons  |<------------------------------------------+
|----------|
| id PK    |
| name     |
| price    |
+----------+

+----------+
| articles |
|----------|
| id PK    |
| title    |
| slug     |
| content  |
+----------+
```

## Deployment Guide

### Checklist Production

- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Set `APP_URL` ke domain production.
- Gunakan database production yang persistent.
- Set credential database.
- Set credential Xendit jika payment aktif.
- Jalankan `php artisan storage:link`.
- Jalankan migration dengan `--force`.
- Jalankan build frontend.
- Aktifkan HTTPS.
- Pastikan permission folder `storage/` dan `bootstrap/cache/` dapat ditulis oleh web server.
- Konfigurasi queue worker.
- Konfigurasi scheduler jika nanti ada command berkala.

### Deployment ke VPS

Contoh flow umum:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Restart PHP-FPM dan queue worker:

```bash
sudo systemctl reload php8.3-fpm
php artisan queue:restart
```

Contoh konfigurasi queue worker dengan Supervisor:

```ini
[program:shoe-laundry-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/shoe-laundry/artisan queue:work --tries=3 --timeout=90
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/shoe-laundry/storage/logs/worker.log
stopwaitsecs=3600
```

### Deployment dengan Docker

Folder `docker/` tersedia, tetapi detail Dockerfile/compose belum didokumentasikan di repo ini.

```text
[TODO: isi instruksi Docker setelah konfigurasi docker final]
```

Template minimal yang perlu tersedia:

```bash
docker compose up -d --build
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
```

### Xendit Production Notes

- Simpan `XENDIT_SECRET_KEY` hanya di secret manager/server env.
- Jangan commit secret key ke repository.
- Daftarkan webhook callback di dashboard Xendit.
- Pastikan route webhook dibuat dan mengarah ke `PaymentController@webhook`.
- Validasi `x-callback-token` harus cocok dengan `XENDIT_WEBHOOK_VERIFICATION_TOKEN`.

Contoh route yang dapat ditambahkan bila belum ada:

```php
Route::post('/payment/xendit/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.xendit.webhook');
```

## Testing

Project menggunakan PHPUnit via Laravel test runner.

### Menjalankan Semua Test

```bash
composer test
```

Atau:

```bash
php artisan test
```

### Unit Test

```bash
php artisan test --testsuite=Unit
```

### Feature Test

```bash
php artisan test --testsuite=Feature
```

### Coverage Report

Coverage membutuhkan driver seperti Xdebug atau PCOV.

```bash
php artisan test --coverage
```

HTML coverage:

```bash
php artisan test --coverage-html coverage
```

Area test yang sebaiknya diprioritaskan:

- Login owner dan pembatasan login karyawan.
- CRUD order.
- Generate `order_number` dan `tracking_code`.
- Tracking publik dengan 4 digit telepon.
- Voucher check.
- Export Excel.
- Xendit webhook.

## Branching Strategy

Strategi branch yang disarankan:

| Branch | Fungsi |
| --- | --- |
| `main` | Source production/stable |
| `develop` | Integrasi fitur sebelum release |
| `feature/<nama-fitur>` | Pengembangan fitur baru |
| `fix/<nama-bug>` | Perbaikan bug non-urgent |
| `hotfix/<nama-issue>` | Perbaikan cepat untuk production |
| `release/<versi>` | Persiapan rilis |
| `chore/<nama-task>` | Maintenance, dependency, konfigurasi |

Contoh:

```bash
git checkout -b feature/order-tracking-improvement
git checkout -b fix/voucher-validation
git checkout -b hotfix/xendit-webhook-token
```

## Commit Convention

Gunakan Conventional Commits.

Format:

```text
<type>(optional-scope): <summary>
```

Type yang digunakan:

| Type | Keterangan |
| --- | --- |
| `feat` | Fitur baru |
| `fix` | Perbaikan bug |
| `docs` | Dokumentasi |
| `style` | Formatting tanpa perubahan behavior |
| `refactor` | Refactor tanpa fitur/bugfix |
| `test` | Penambahan/perubahan test |
| `chore` | Maintenance dependency/config |
| `build` | Build system |
| `ci` | CI/CD |
| `perf` | Perbaikan performa |

Contoh:

```bash
git commit -m "feat(order): add public tracking validation"
git commit -m "fix(voucher): prevent expired voucher usage"
git commit -m "docs(readme): add deployment checklist"
```

## Contribution Guide

Flow kontribusi:

1. Ambil update terbaru.

```bash
git checkout develop
git pull origin develop
```

2. Buat branch baru.

```bash
git checkout -b feature/nama-fitur
```

3. Install dependency dan setup lokal jika belum.

```bash
composer install
npm install
php artisan migrate --seed
```

4. Kerjakan perubahan sesuai scope.

5. Jalankan formatting dan test.

```bash
./vendor/bin/pint
composer test
npm run build
```

6. Commit perubahan.

```bash
git add .
git commit -m "feat(scope): summary singkat"
```

7. Push branch.

```bash
git push origin feature/nama-fitur
```

8. Buat Pull Request ke `develop`.

## Pull Request & Code Review

### Checklist Sebelum Submit PR

- [ ] Scope PR jelas dan tidak mencampur banyak fitur.
- [ ] Migration aman untuk data existing.
- [ ] Tidak ada secret/API key di commit.
- [ ] `.env.example` diperbarui jika ada env baru.
- [ ] Test relevan sudah ditambahkan atau diperbarui.
- [ ] `composer test` berhasil.
- [ ] `npm run build` berhasil.
- [ ] `./vendor/bin/pint` sudah dijalankan.
- [ ] Screenshot disertakan untuk perubahan UI.
- [ ] Dokumentasi diperbarui jika behavior berubah.

### Reviewer

```text
[TODO: isi aturan reviewer, misalnya minimal 1 owner/maintainer]
```

Rekomendasi:

- Minimal 1 approval dari maintainer untuk perubahan normal.
- Minimal 2 approval untuk perubahan database, auth, payment, atau deployment.
- Owner/tech lead wajib review untuk perubahan payment dan permission.

### Kriteria Approval

- Behavior sesuai requirement.
- Tidak menurunkan keamanan auth, payment, dan data pelanggan.
- Error handling jelas.
- Validasi request cukup.
- Perubahan database backward-compatible atau memiliki migration plan.
- Kode mengikuti standar Laravel dan pola yang sudah ada.

## Coding Standards

### PHP / Laravel

- Ikuti konvensi Laravel untuk controller, model, migration, request validation, dan route naming.
- Gunakan Eloquent model untuk akses data.
- Validasi input di controller atau Form Request.
- Hindari menaruh business logic besar di Blade.
- Gunakan named route untuk redirect/link internal.
- Gunakan middleware untuk authorization berulang.
- Jangan commit file `.env`.

Formatting:

```bash
./vendor/bin/pint
```

### Blade

- Simpan view admin di `resources/views/admin`.
- Simpan komponen reusable di `resources/views/components`.
- Gunakan escaping Blade default `{{ }}` untuk output user-generated content.
- Gunakan `{!! !!}` hanya jika konten sudah disanitasi.

### JavaScript & CSS

- Entry point frontend:
  - `resources/js/app.js`
  - `resources/css/app.css`
- Gunakan Vite untuk build.
- Gunakan utility Tailwind secara konsisten.

```bash
npm run dev
npm run build
```

### Naming Convention

| Item | Convention | Contoh |
| --- | --- | --- |
| Controller | PascalCase + `Controller` | `OrderController` |
| Model | PascalCase singular | `Voucher` |
| Migration | snake_case descriptive | `create_vouchers_table` |
| Route name | dot notation | `admin.orders.export` |
| Blade file | kebab-case/snake sesuai folder | `orders-create.blade.php` |
| Database table | snake_case plural | `orders`, `add_ons` |
| Column | snake_case | `payment_status` |

### Security Standards

- Jangan menyimpan secret di repository.
- Set `APP_DEBUG=false` di production.
- Gunakan HTTPS di production.
- Validasi semua request.
- Batasi akses admin dengan middleware.
- Audit endpoint API sebelum dibuka ke publik.
- Pastikan upload image hanya menerima tipe file yang aman.

## Troubleshooting & FAQ

### 1. `APP_KEY` belum diset

Gejala:

```text
No application encryption key has been specified.
```

Solusi:

```bash
php artisan key:generate
```

### 2. Database SQLite tidak ditemukan

Gejala:

```text
Database file at path ... does not exist
```

Solusi:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

PowerShell:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
php artisan migrate --seed
```

### 3. Asset CSS/JS tidak muncul

Solusi development:

```bash
npm install
npm run dev
```

Solusi production:

```bash
npm ci
npm run build
php artisan view:clear
```

### 4. Gambar artikel tidak muncul

Solusi:

```bash
php artisan storage:link
```

Pastikan web server dapat membaca folder `public/storage`.

### 5. Login owner gagal

Pastikan seeder sudah dijalankan:

```bash
php artisan migrate:fresh --seed
```

Gunakan akun:

```text
owner@laundry.com / password
```

### 6. Login karyawan ditolak

Ini behavior yang saat ini ada di kode. Flow `/dashboard` akan logout user non-owner dan menampilkan pesan login karyawan dinonaktifkan. API login juga menolak role `karyawan`.

```text
[TODO: tentukan apakah role karyawan akan diaktifkan lagi atau tetap hanya owner]
```

### 7. Voucher selalu gagal

Cek hal berikut:

- `is_active = true`.
- Tanggal hari ini berada antara `valid_from` dan `valid_until`.
- `used_count < max_uses`.
- Subtotal memenuhi `min_order`.
- Kode voucher sesuai huruf besar/kecil yang tersimpan.

### 8. Export Excel error

Pastikan package terinstall:

```bash
composer install
```

Jika error permission, pastikan folder storage writable:

```bash
chmod -R ug+rwx storage bootstrap/cache
```

### 9. Xendit invoice gagal dibuat

Cek:

- `XENDIT_SECRET_KEY` sudah terisi.
- Amount order lebih dari 0.
- Server dapat mengakses API Xendit.
- Package `xendit/xendit-php` terinstall.

### 10. Webhook Xendit tidak mengubah status pembayaran

Cek:

- Route webhook sudah didaftarkan.
- URL webhook sudah diset di dashboard Xendit.
- Header `x-callback-token` sesuai `XENDIT_WEBHOOK_VERIFICATION_TOKEN`.
- Format `external_id` sesuai `ORDER-{order_number}-{timestamp}`.

### 11. Route cache bermasalah setelah deploy

Solusi:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Changelog

Project ini disarankan mengikuti format [Keep a Changelog](https://keepachangelog.com/) dan Semantic Versioning.

Format:

```markdown
## [Unreleased]

### Added
- Fitur baru yang belum dirilis.

### Changed
- Perubahan behavior yang sudah ada.

### Fixed
- Bug yang diperbaiki.

### Removed
- Fitur yang dihapus.
```

Contoh:

```markdown
## [0.1.0] - 2026-05-21

### Added
- Dokumentasi README untuk maintenance jangka panjang.
- Dokumentasi API, database schema, deployment, testing, dan contribution flow.
```

Riwayat awal:

## [0.1.0] - 2026-05-21

### Added

- README lengkap untuk maintenance project.

## License

Project ini menggunakan lisensi MIT berdasarkan `composer.json`.

```text
MIT License

[TODO: tambahkan teks lisensi lengkap di file LICENSE jika repository akan dipublikasikan]
```
