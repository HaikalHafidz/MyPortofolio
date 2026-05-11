<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Koneksi ke database
$servername = "localhost";
$username = "portfolio_user";
$password = "password";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_donasi = isset($_POST['kode']) ? trim($_POST['kode']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if (empty($kode_donasi) || empty($status)) {
        echo json_encode(['success' => false, 'error' => 'Parameter tidak lengkap']);
        exit();
    }

    // Update status donasi
    $sql = "UPDATE donasi SET status = ? WHERE kode_donasi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status, $kode_donasi);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Gagal update status: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Method tidak diizinkan']);
}

$conn->close();
?>