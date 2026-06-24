<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Koneksi ke database
$servername = "localhost";
$username = "portfolio_user";
$password = "password";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit();
}

// Query untuk mengambil data donasi
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM donasi";
if (!empty($search)) {
    $sql .= " WHERE kode_donasi LIKE ? OR nama LIKE ? OR email LIKE ?";
}
$sql .= " ORDER BY tanggal DESC";

$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();

$donations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $donations[] = [
            'kode_donasi' => $row['kode_donasi'],
            'nama' => $row['nama'],  // Dihapus htmlspecialchars, karena sudah di-escape di JavaScript
            'email' => $row['email'],
            'jumlah' => $row['jumlah'],
            'metode_pembayaran' => $row['metode_pembayaran'],
            'status' => $row['status'],
            'tanggal' => $row['tanggal']
        ];
    }
}

echo json_encode($donations);
$stmt->close();
$conn->close();
?>