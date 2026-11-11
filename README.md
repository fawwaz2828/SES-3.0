# SES 4.0 — Sustain Energy Solution

Website smart lighting berbasis PHP 8 + MySQL, dengan UI full Tailwind CSS.

## Struktur folder

```
SES-4.0/
├── index.php               Landing page
├── explore.php             Katalog produk
├── product.php             Detail produk
├── blog.php                Daftar artikel (cari + filter kategori)
├── article.php             Detail artikel
├── login.php · register.php · logout.php
│
├── dashboard.php           [user] Daftar konsultasi
├── consultation-form.php   [user] Form booking konsultasi
├── consultation.php        [user] Detail konsultasi
├── devices.php             [user] Daftar + tambah device
├── profile.php             [user] Profil
├── profile-edit.php        [user] Edit profil + upload avatar
│
├── admin/                  Panel admin
│   ├── login.php · logout.php
│   ├── dashboard.php       Statistik + konsultasi terbaru
│   ├── consultations.php   Kelola konsultasi
│   ├── user.php            Detail user
│   ├── products.php        Kelola produk
│   └── blogs.php           Kelola artikel
│
├── src/                    Kode aplikasi (namespace Ses\, PSR-4)
│   ├── App.php · Config.php · Database.php
│   ├── Model/              Baris database sebagai objek readonly (Product, Article, Consultation, …)
│   ├── Dto/                Input form + validasi (Registration, ConsultationRequest, …)
│   ├── Enum/               ConsultationStatus, FlashType, ImageType
│   ├── Repository/         Semua query SQL
│   ├── Session/            Auth, Flash, Csrf
│   ├── Http/ · Service/    Upload gambar, penyimpanan avatar
│   └── Support/            Input & Row (baca nilai bertipe dari request / database)
│
├── includes/
│   ├── bootstrap.php       Dipanggil di awal setiap halaman (autoload, session, DB, CSRF)
│   ├── config.php          Setting database
│   ├── helpers.php         Fungsi kecil untuk template: e(), url(), format_date(), …
│   ├── layout/             Head (Tailwind), header/navbar, footer, layout auth & admin
│   ├── partials/           Komponen yang dipakai ulang (kartu produk, kartu artikel)
│   └── sections/           Section-section landing page
│
├── assets/images/          Gambar statis
├── uploads/                Avatar user (dibuat otomatis, tidak masuk git)
└── database/ses.sql        Dump database (schema + data awal)
```

## Menjalankan secara lokal (XAMPP)

1. Jalankan **MySQL** dari XAMPP Control Panel.
2. Buat database `ses` di phpMyAdmin, lalu import `database/ses.sql`.
3. Default koneksi: user `root` tanpa password. Kalau MySQL kamu memakai password,
   buat file `includes/config.local.php` (file ini tidak ikut ke git):
   ```php
   <?php return ['db_pass' => 'password_kamu'];
   ```
4. Jalankan server, pilih salah satu:
   - Dari folder project: `C:\xampp\php\php.exe -S 127.0.0.1:8000`, lalu buka http://127.0.0.1:8000
   - Atau salin folder ke `C:\xampp\htdocs\`, nyalakan Apache, lalu buka http://localhost/SES-4.0/

Panel admin ada di `/admin/login.php`.

## Mengubah tampilan (Tailwind CSS)

CSS sudah di-compile ke `assets/css/app.css` dan ikut di-commit, jadi website langsung jalan tanpa Node.js.
Kalau kamu menambah atau mengubah class Tailwind, compile ulang:

```bash
npm install          # sekali saja
npm run watch:css    # selama mengedit (otomatis compile ulang)
npm run build:css    # sebelum commit
```

Sumbernya ada di `assets/css/input.css`. Di sana juga didefinisikan komponen liquid glass (`.glass`,
`.glass-dark`, `.card`, `.btn-*`, `.input`, `.pill-*`). Varian `dark:` aktif saat lampu di landing page
masih mati (class `lights-off` di `<html>`).

Website tetap jalan **tanpa** Composer, karena `bootstrap.php` punya autoloader cadangan.
Composer hanya dibutuhkan untuk tooling kualitas kode di bawah.

## Kualitas kode

Project ini mengikuti [php-modernization-skill](https://github.com/netresearch/php-modernization-skill):
`declare(strict_types=1)` di semua file, PHP 8.2, PHPStan level 9, PHP-CS-Fixer `@PER-CS`, dan Rector.

```bash
composer install
composer cs:check        # cek gaya kode (PER-CS)
composer cs:fix          # rapikan otomatis
composer phpstan         # analisis tipe, level 9
composer rector:check    # saran refactor (dry-run)
composer qa              # jalankan semuanya
```

## Aturan saat menambah kode

- Query SQL hanya ditulis di `src/Repository/`, selalu dengan parameter `?`.
- Data dari database dibaca lewat model (`Product::fromRow()`, dst.), bukan array mentah.
- Input form dibaca lewat DTO di `src/Dto/` (`fromArray($_POST)` + `errors()`).
- Nilai tetap (status, tipe) memakai enum di `src/Enum/`.
- Semua output ditampilkan lewat `e()`. Setiap form POST wajib menyertakan `<?= csrf_field() ?>`.
- Styling hanya memakai class Tailwind. Komponen dasar (`.btn`, `.input`, `.card`, `.badge`, `.pill`)
  ada di `includes/layout/head.php`.
