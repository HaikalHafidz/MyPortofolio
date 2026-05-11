# Portofolio Web Muhammad Haikal Hafidz

Repositori ini berisi proyek website portofolio pribadi milik Muhammad Haikal Hafidz, lengkap dengan halaman kontak, donasi, dan informasi tentang pendidikan, pengalaman, serta keahlian.

## Ringkasan

- **Nama**: Muhammad Haikal Hafidz
- **Jenis proyek**: Website portofolio statis dengan fitur kontak dan donasi
- **Teknologi**: HTML, CSS, JavaScript, PHP
- **File pendukung**: `database.sql`, PHP backend untuk kontak/donasi

## Isi Repo

- `index.html` - Halaman utama portofolio
- `donate.html` - Halaman donasi
- `riwayat_donasi.html` - Halaman riwayat donasi
- `konfirmasi_donasi.html` - Halaman konfirmasi donasi
- `contact_success.html` - Halaman sukses setelah mengirim pesan
- `contact.php` - Backend form kontak
- `process_donation.php` - Backend proses donasi
- `api_donasi.php` - API donasi
- `update_donasi_status.php` - Update status donasi
- `database.sql` - Script skema database
- `script.js` - JavaScript interaktif
- `css/style.css` - Gaya tampilan website
- `cv/` - Folder untuk file CV PDF

## Fitur

- Profil personal dan informasi kontak
- Bagian "Tentang Saya", "Pendidikan", "Pengalaman", dan "Keahlian"
- Form kontak untuk mengirim pesan
- Sistem donasi dan halaman konfirmasi
- Download CV dalam format PDF

## Cara Menjalankan

1. Tempatkan repo ini di server web lokal (misalnya XAMPP, WAMP, atau LAMP).
2. Pastikan file PHP dapat diakses dari web server.
3. Import `database.sql` ke MySQL jika ingin menggunakan fitur donasi dengan database.
4. Buka `index.html` di browser atau akses localhost via server web lokal.

## Catatan

- Jika ingin menggunakan fitur download CV, letakkan file CV PDF di folder `cv/` atau sesuaikan path di `index.html`.
- Pastikan `contact.php`, `process_donation.php`, `api_donasi.php`, dan `update_donasi_status.php` ada di direktori server yang sama.

## Lisensi

Proyek ini dapat digunakan sebagai portofolio pribadi. Sesuaikan lisensi jika ingin membagikannya secara publik.
