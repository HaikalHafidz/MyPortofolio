<?php
/**
 * Konfigurasi utama.
 * File ini WAJIB ada di folder php/ (satu folder dengan contact.php & db_test.php).
 * Sebelumnya file ini belum ada sama sekali — itu sebabnya pesan dari form kontak
 * tidak pernah tersimpan/terkirim (getDbConnection() gagal dipanggil).
 */

// ==== Kredensial Database (isi sesuai hosting/cPanel/XAMPP kamu) ====
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // ganti sesuai username database hosting
define('DB_PASS', '');            // ganti sesuai password database hosting
define('DB_NAME', 'portfolio_db');

// ==== Notifikasi WhatsApp via CallMeBot (gratis) ====
// Cara dapat API key:
// 1. Simpan nomor +34 644 59 71 67 di kontak HP kamu.
// 2. Kirim pesan WhatsApp ke nomor itu, isi: "I allow callmebot to send me messages"
// 3. Bot akan membalas dengan API key kamu, salin ke bawah ini.
define('WA_PHONE', '62895433210605'); // nomor WhatsApp KAMU (yang menerima notifikasi), format 62xxxxxxxxxx tanpa tanda +
define('WA_APIKEY', 'YOUR_CALLMEBOT_APIKEY'); // ganti dengan API key dari CallMeBot

/**
 * Membuka koneksi database MySQL.
 * @throws RuntimeException jika koneksi gagal.
 */
function getDbConnection(): mysqli
{
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new RuntimeException('Koneksi database gagal: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
