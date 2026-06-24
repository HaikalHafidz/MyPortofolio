<?php
// db_test.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "portfolio_user";
$password = "password";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

echo "<h3>Database connection test</h3>";
echo "<p>Berhasil terhubung ke database: <strong>$dbname</strong></p>";

// Periksa tabel utama yang ada
$tables = ['contacts', 'donasi'];

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<p>Tabel <strong>$table</strong> ditemukan.</p>";

        $countResult = $conn->query("SELECT COUNT(*) AS total FROM $table");
        if ($countResult) {
            $row = $countResult->fetch_assoc();
            echo "<p>Jumlah baris di <strong>$table</strong>: " . $row['total'] . "</p>";
            $countResult->free();
        }
    } else {
        echo "<p style='color:red;'>Tabel <strong>$table</strong> tidak ditemukan.</p>";
    }
    if ($result) {
        $result->free();
    }
}

// Tes query sederhana pada tabel donasi
$donasiResult = $conn->query("SELECT id, kode_donasi, nama, jumlah, status FROM donasi ORDER BY id DESC LIMIT 3");
if ($donasiResult) {
    if ($donasiResult->num_rows > 0) {
        echo "<h4>3 Data donasi terbaru</h4>";
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Kode</th><th>Nama</th><th>Jumlah</th><th>Status</th></tr>";
        while ($row = $donasiResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['kode_donasi']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
            echo "<td>" . htmlspecialchars($row['jumlah']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tabel donasi kosong.</p>";
    }
    $donasiResult->free();
} else {
    echo "<p style='color:red;'>Query donasi gagal: " . $conn->error . "</p>";
}

$conn->close();
?>