# 🌸 Sistem Penjualan Bucket Bunga

Halo! Ini project tugas kuliah saya, **Sistem Penjualan Bucket Bunga** atau biasa saya sebut **Bucket House**.

Project ini adalah website toko online yang menjual bucket bunga. Pembeli bisa lihat katalog, custom order, checkout, dan bayar. Admin bisa kelola produk, pesanan, dan cetak invoice.

---

## Tentang Saya

Nama    : Indah Permata Sari |
NIM     :2401091009 
Kelas   : MI 3A 
Jurusan : Teknologi Informasi 
Prodi   : Manajemen Informatika 
Kampus  : Politeknik Negeri Padang 

Project ini dibuat untuk memenuhi tugas mata kuliah **Pemrograman Web Framework**.

---

##  Fitur Website

### Untuk Pembeli (User)
- Daftar akun & login (bisa pakai email atau nomor HP)
- Lihat katalog bucket bunga
- Filter produk berdasarkan kategori
- Lihat detail produk
- Custom order — bisa request bunga, boneka, uang, warna kertas, pita, dan pesan ucapan
- Tambah ke keranjang & checkout
- Pilih metode pembayaran (E-Wallet atau Cash)
- Atur jadwal pengiriman
- Kasih ulasan & rating bintang

### Untuk Admin (Penjual)
- Dashboard admin dengan sidebar
- Tambah, edit, hapus produk
- Upload gambar produk
- Kelola kategori produk
- Lihat pesanan yang masuk
- Update status pengiriman
- Atur stok bahan baku
- Cetak invoice

---


## 📅 Progress Tugas

Ini progress saya per minggu. Yang udah ada tanda ✅ berarti selesai:

- [x] **Minggu 1 — 6 Okt 2026:** Login, Register & Logout ✅
- [x] **Minggu 2 — 13 Okt 2026:** CRUD Produk, Kategori & Upload Gambar ✅
- [ ] **Minggu 3 — 20 Okt 2026:** Landing Page & Detail Produk
- [ ] **Minggu 4 — 27 Okt 2026:** Fitur Rakit Buket Custom
- [ ] **Minggu 5 — 3 Nov 2026:** Keranjang & Checkout
- [ ] **Minggu 6 — 10 Nov 2026:** Pembayaran Digital & Cash
- [ ] **Minggu 7 — 17 Nov 2026:** Pilih Jadwal Pengiriman
- [ ] **Minggu 8 — 24 Nov 2026:** Kelola Pesanan & Update Status
- [ ] **Minggu 9 — 1 Des 2026:** Sistem Ulasan & Rating
- [ ] **Minggu 10 — 8 Des 2026:** Testing & Optimasi
- [ ] **Minggu 11 — 15 Des 2026:** Upload ke Hosting

---

##  Cara Menjalankan Project

Kalau mau coba jalanin project ini di komputer kamu, ikuti langkah berikut:

### Yang Perlu Diinstall Dulu
- **PHP** versi 8.2 ke atas
- **Composer** (buat install library PHP)
- **MariaDB** atau **MySQL** (buat database)
- **Node.js & NPM** (buat asset frontend)
- **Git** (buat clone project)

### Langkah-langkah

```bash
# 1. Clone project dari GitHub
git clone https://github.com/indahpermatasari975/sistem-penjualan-bucket.git

# 2. Masuk ke folder project
cd sistem-penjualan-bucket

# 3. Install library PHP
composer install

# 4. Install library JavaScript
npm install

# 5. Copy file .env
cp .env.example .env

# 6. Bikin application key
php artisan key:generate

# 7. Buka file .env, atur bagian database:
# DB_CONNECTION=mariadb
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sistem_penjualan_bucket
# DB_USERNAME=root
# DB_PASSWORD=

# 8. Bikin database baru di HeidiSQL / phpMyAdmin dengan nama:
# sistem_penjualan_bucket

# 9. Jalankan migrasi & seeder (bikin tabel + isi data contoh)
php artisan migrate:fresh --seed

# 10. Bikin link buat akses gambar
php artisan storage:link

# 11. Jalankan server
php artisan serve
