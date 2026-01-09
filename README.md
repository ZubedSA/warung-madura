# Warung Madura Management System

Sistem manajemen warung kelontong kecil (model Warung Madura) yang **SEDERHANA, REALISTIS, dan MUDAH DIPAKAI**.

## 🎯 Fitur Utama

### Untuk Penjaga Warung
- ✅ **Stok Barang** - Monitor stok dengan status (Banyak/Cukup/Sedikit/Kosong)
- ✅ **Order Barang** - Buat order ke supplier dengan integrasi WhatsApp
- ✅ **Pemasukan** - Input total pemasukan harian
- ✅ **Pengeluaran** - Catat pengeluaran dengan kategori
- ✅ **Riwayat Keuangan** - Lihat ringkasan keuangan

### Untuk Pemilik/Admin
- ✅ **Dashboard** - Ringkasan untung/rugi dan status warung
- ✅ **Monitoring Order** - Pantau semua order penjaga
- ✅ **Log Aktivitas** - Audit trail semua aktivitas
- ✅ **Laporan Harian** - Detail pemasukan & pengeluaran per hari
- ✅ **Laporan Bulanan** - Ringkasan untung/rugi per bulan
- ✅ **Master Data** - Kelola kategori, barang, dan pengguna

## 📋 Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js (optional untuk asset compilation)

### Langkah Instalasi

1. **Clone atau download project**
   ```bash
   cd d:\web\WARUNG MADURA SISTEM
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate app key**
   ```bash
   php artisan key:generate
   ```

5. **Buat database**
   ```sql
   CREATE DATABASE warung_madura;
   ```

6. **Jalankan migration dan seeder**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan server**
   ```bash
   php artisan serve
   ```

8. **Akses aplikasi**
   ```
   http://localhost:8000
   ```

## 🔐 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Pemilik/Admin | pemilik@warung.com | password |
| Penjaga | penjaga@warung.com | password |

## 📱 Screenshots

Aplikasi ini menggunakan desain **Mobile First** dengan tampilan premium:
- Dark theme yang elegan
- Tombol besar dan mudah ditekan
- Status dengan warna yang jelas
- Bahasa Indonesia yang sederhana

## 🏗️ Tech Stack

- **Backend:** PHP 8.2+ / Laravel 11
- **Database:** MySQL 8 / MariaDB
- **Frontend:** Blade + Custom CSS
- **Icons:** Emoji (native)
- **Fonts:** Inter (Google Fonts)

## 📁 Struktur Project

```
app/
├── Http/
│   ├── Controllers/    # 10 Controllers
│   └── Middleware/     # 3 Middleware
├── Models/             # 8 Models
└── Providers/

database/
├── migrations/         # 10 Migrations
└── seeders/           # 1 Seeder (with sample data)

resources/views/
├── layouts/           # App layout + partials
├── auth/             # Login page
├── penjaga/          # Penjaga views
└── admin/            # Admin views

public/
└── css/app.css       # Premium CSS (full design system)
```

## 📊 Database Schema

| Table | Deskripsi |
|-------|-----------|
| users | Pengguna (penjaga/pemilik) |
| categories | Kategori barang |
| products | Daftar barang dengan status stok |
| orders | Order ke supplier |
| order_items | Item dalam order |
| incomes | Pemasukan harian |
| expenses | Pengeluaran (belanja/listrik/air/lainnya) |
| audit_logs | Log aktivitas |

## 🎨 Konsep Stok

Sistem menggunakan **status-based stock** bukan angka:

| Status | Warna | Arti |
|--------|-------|------|
| 🟢 Banyak | Hijau | Stok masih banyak |
| 🔵 Cukup | Biru | Stok cukup |
| 🟠 Sedikit | Orange | Stok menipis |
| 🔴 Kosong | Merah | Stok habis |

## 📞 Integrasi WhatsApp

Saat order dikirim, sistem otomatis membuat pesan WhatsApp yang siap dikirim ke supplier dengan format:
- Nomor order
- Tanggal
- Daftar barang dan jumlah
- Catatan tambahan

## 📄 License

MIT License

---

Made with ❤️ for Warung Madura
