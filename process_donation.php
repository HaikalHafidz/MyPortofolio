<?php
$servername = "localhost";
$username = "portfolio_user";
$dbname = "portfolio_db"; 
$password = "password";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

function bersihkan_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan data
    $nama = bersihkan_input($_POST['nama']);
    $email = bersihkan_input($_POST['email']);
    $jumlah = bersihkan_input($_POST['jumlah']);
    $metode_pembayaran = isset($_POST['metode_pembayaran']) ? bersihkan_input($_POST['metode_pembayaran']) : '';
    $pesan = isset($_POST['pesan']) ? bersihkan_input($_POST['pesan']) : '';
    
    $errors = [];
    
    if (empty($nama)) {
        $errors[] = "empty_fields";
    }
    
    // Validasi email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "invalid_email";
    }
    
    if (empty($jumlah) || !is_numeric($jumlah) || $jumlah <= 0) {
        $errors[] = "invalid_amount";
    }
    
    if (empty($metode_pembayaran)) {
        $errors[] = "empty_fields";
    }
    
    // Jika ada error, redirect kembali ke form
    if (!empty($errors)) {
        $error_param = $errors[0];
        $query_params = http_build_query([
            'error' => $error_param,
            'nama' => $nama,
            'email' => $email,
            'jumlah' => $jumlah,
            'pesan' => $pesan
        ]);
        header("Location: donate.html?" . $query_params);
        exit();
    }
    
    $kode_donasi = 'DON' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    
    $status = 'pending'; // Status donasi
    
    // Simpan ke database
    $sql = "INSERT INTO donasi (kode_donasi, nama, email, jumlah, metode_pembayaran, pesan, status, tanggal) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdsss", $kode_donasi, $nama, $email, $jumlah, $metode_pembayaran, $pesan, $status);
    
    if ($stmt->execute()) {
        $query_params = http_build_query([
            'kode' => $kode_donasi,
            'nama' => $nama,
            'email' => $email,
            'jumlah' => $jumlah,
            'metode' => $metode_pembayaran
        ]);
        header("Location: konfirmasi_donasi.html?" . $query_params);  // Diperbaiki: .html bukan .php
        exit();
    } else {
        error_log("Error executing SQL: " . $stmt->error);
        header("Location: donate.html?error=db_error");
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: donate.html");
    exit();
}
?>