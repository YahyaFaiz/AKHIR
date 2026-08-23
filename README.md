# 🗺️ WebGIS Pelaporan Kerusakan Fasilitas Kampus berbasis LBS & Geofencing dengan Validasi Partisipatif

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Leaflet JS](https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=leaflet&logoColor=white)](https://leafletjs.com)
[![MySQL](https://img.shields.io/badge/MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=blue)](https://alpinejs.dev)
[![PWA Ready](https://img.shields.io/badge/PWA-Ready-orange?style=for-the-badge&logo=progressive-web-apps&logoColor=white)](#)

Aplikasi **WebGIS Pelaporan Kerusakan Fasilitas Kampus** merupakan sistem pelaporan digital berbasis geospasial yang dirancang khusus untuk lingkungan **Universitas Tanjungpura (UNTAN)**. Dengan mengintegrasikan **Location-Based Services (LBS)** dan **Geofencing**, sistem ini membatasi pembuatan laporan hanya bagi pengguna yang secara fisik berada di dalam batas area kampus resmi. 

Aplikasi ini memperkenalkan inovasi **Validasi Partisipatif (Voting)** yang mengatasi masalah duplikasi data dan laporan palsu secara otomatis, sekaligus menerapkan manajemen birokrasi yang realistis melalui **Multi-Level Admin (RBAC)** dan integrasi penugasan teknisi secara offline menggunakan **Cetak Rekap Laporan**.

---

## 🚀 Fitur Unggulan (Core Innovation)

### 1. 📍 Location-Based Services (LBS) & Geofencing
*   **Akurasi Posisi:** Sistem mengambil koordinat *real-time* (latitude & longitude) dari GPS perangkat pelapor untuk melakukan verifikasi lokasi.
*   **Batas Virtual (Geofencing):** Menggunakan library **Leaflet.js** dan **Turf.js** untuk memetakan batas area kampus UNTAN dalam bentuk poligon. Laporan hanya akan diterima jika koordinat GPS pelapor terbukti valid berada di dalam area poligon geofencing kampus.

### 2. 👥 Validasi Partisipatif (Voting Anti-Duplikasi)
*   **Saksi Mata Digital:** Jika sistem mendeteksi adanya laporan dengan kategori dan lokasi yang sama dalam radius tertentu, pelapor selanjutnya tidak akan diarahkan membuat laporan baru, melainkan diberikan opsi untuk memberikan **Voting Dukungan**.
*   **Radius Pengunci (±20–30 m):** Hanya pengguna lain yang berada dalam radius ±20–30 meter dari titik kerusakan yang diizinkan melakukan voting guna memastikan kevalidan laporan di lapangan.
*   **Solusi Masalah Duplikasi:** Fitur ini secara langsung menyelesaikan kendala duplikasi laporan yang sering dialami oleh sistem manajemen konvensional (merujuk pada rekomendasi *Jurnal Amielia, dkk., 2025*).

### 3. 🏢 Multi-Level Admin berbasis Role (RBAC)
Mendukung alur birokrasi kampus yang realistis tanpa merumitkan kodingan sistem:
*   **Super Admin (Tingkat Universitas/UNTAN-1):** Memiliki kontrol penuh atas seluruh sistem, geofencing area, dan penanganan kerusakan fasilitas umum lintas fakultas.
*   **Admin Fakultas:** Memiliki hak akses khusus untuk mengelola, menyetujui, atau menolak laporan kerusakan yang terjadi di area fakultasnya sendiri.
*   **Admin Prodi (Opsional):** Berfokus pada pengelolaan inventaris atau sarana spesifik di bawah prodi (misalnya peralatan laboratorium komputer).

### 📄 4. Cetak Rekap Laporan (Offline Technician Delegation)
*   **Birokrasi Ramping:** Tidak memerlukan login/akun khusus bagi petugas lapangan (teknisi). Admin cukup mengunduh rekapitulasi laporan kerusakan yang tervalidasi ke dalam format **PDF/Excel** sebagai Surat Perintah Kerja fisik untuk teknisi di lapangan.

### 📱 5. Progressive Web Apps (PWA)
*   Aplikasi dikembangkan dengan konsep **PWA** agar dapat diakses secara instan oleh seluruh civitas akademika melalui browser, serta dapat "diinstal" langsung di layar utama smartphone layaknya aplikasi mobile asli tanpa perlu mengunduh APK.

---

## 🛠️ Tech Stack

### Sisi Server (Backend)
*   **Framework:** Laravel (PHP) dengan arsitektur MVC (Model-View-Controller)
*   **Database:** MySQL (Relational Database Management System)

### Sisi Klien (Frontend & Interactive)
*   **Styling:** Tailwind CSS (Modern, Clean, Responsive Layout)
*   **Interactivity:** Alpine.js (Ringan & Deklaratif untuk fungsionalitas UI)
*   **Mapping & GIS:** Leaflet JS (Open-source mapping library)
*   **Geospatial Processing:** Turf.js (Pustaka analisis data spasial berbasis vektor pada browser)

---

## 🗺️ Arsitektur & Alur Diagram

### Use Case Diagram (3 Aktor - Ramping & Efisien)
Sistem ini menggunakan arsitektur Use Case yang efisien dengan memisahkan hak akses Admin secara dinamis (*Role-Based Access Control*):

```
                                    +------------------------------+
                                    |   Sistem Pelaporan WebGIS    |
                                    +------------------------------+
                                    |                              |
  +-------------+                   |      +------------------+    |
  |  Pelapor    |------------------------->| Kirim Laporan    |    |
  |  (Mhs/Dsn)  |                   |      +------------------+    |
  +-------------+                   |               |              |
                                    |               v              |
                                    |      +------------------+    |
                                    |      | Validasi Lokasi  |    |
                                    |      +------------------+    |
                                    |               |              |
                                    |               v              |
                                    |      +------------------+    |
                                    |      | Voting Laporan   |    |
                                    |      +------------------+    |
                                    |               |              |
  +-------------+                   |               v              |
  |  Admin      |------------------------->| Tinjau Laporan   |    |
  |  (RBAC)     |                   |      +------------------+    |
  +-------------+                   |               |              |
                                    |               v              |
                                    |      +------------------+    |
                                    |      | Cetak Rekap PDF  |    |
                                    |      +------------------+    |
                                    +------------------------------+
```

---

## ⚙️ Cara Instalasi & Konfigurasi

### Prasyarat (Prerequisites)
Pastikan komputer Anda sudah terinstal:
*   PHP >= 8.2
*   Composer
*   MySQL atau MariaDB
*   NPM & Node.js

### Langkah-langkah Setup:

1.  **Clone Repository**
    ```bash
    git clone https://github.com/username/webgis-pelaporan-untan.git
    cd webgis-pelaporan-untan
    ```

2.  **Instalasi Dependensi PHP (Composer)**
    ```bash
    composer install
    ```

3.  **Instalasi Dependensi Javascript (NPM)**
    ```bash
    npm install && npm run build
    ```

4.  **Konfigurasi Environment (.env)**
    Salin file `.env.example` menjadi `.env` dan sesuaikan kredensial database Anda.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Migrasi Database & Seeder**
    ```bash
    php artisan migrate --seed
    ```

6.  **Jalankan Server Lokal**
    ```bash
    php artisan serve
    ```
    Aplikasi kini dapat diakses melalui `http://127.0.0.1:8000`.

---

## 📚 Landasan & Kajian Literatur (Academic Grounding)
Sistem ini dirancang dengan dasar ilmiah yang kuat dan merujuk pada beberapa publikasi penelitian terkait:
1.  **Amielia dkk. (2025):** Merekomendasikan perlunya integrasi sistem guna meminimalisir risiko duplikasi data pada pelaporan fasilitas kampus.
2.  **Maulidya dkk. (2023):** Membuktikan efektivitas algoritma spasial (seperti *Ray-Casting*) dan LBS dalam memvalidasi keaslian kontribusi berdasarkan koordinat lokasi fisik.
3.  **Yusika & Hartono (2025) & Nurhasan (2021):** Menunjukkan efisiensi manajemen penanganan pelaporan dengan pembagian wewenang sarpras dan pembaruan status laporan secara transparan.

---

## 👤 Penulis (Author)
*   **Faiz Diennur Yahya** (NIM D1041221087)
*   **Informatika - Fakultas Teknik, Universitas Tanjungpura** (UNTAN)
*   **Dosen Pembimbing I:** Dr. Ir. Yus Sholva, S.T., M.T.
*   **Dosen Pembimbing II:** Rifqi Anugrah, S.Kom., M.Kom.
