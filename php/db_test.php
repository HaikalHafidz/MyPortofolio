<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/config.php';

try {
    $conn = getDbConnection();
} catch (RuntimeException $e) {
    die('<p style="color:red;">' . htmlspecialchars($e->getMessage()) . '</p>');
}

echo '<h3>Database connection test</h3>';
echo '<p>Berhasil terhubung ke database: <strong>' . htmlspecialchars(DB_NAME) . '</strong></p>';

$tables = ['contacts'];

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<p>Tabel <strong>$table</strong> ditemukan.</p>";

        $countResult = $conn->query("SELECT COUNT(*) AS total FROM $table");
        if ($countResult) {
            $row = $countResult->fetch_assoc();
            echo "<p>Jumlah baris di <strong>$table</strong>: " . $row['total'] . '</p>';
            $countResult->free();
        }
    } else {
        echo "<p style='color:red;'>Tabel <strong>$table</strong> tidak ditemukan. Jalankan schema.sql terlebih dahulu.</p>";
    }
    if ($result) {
        $result->free();
    }
}

$recent = $conn->query('SELECT id, name, email, subject, created_at FROM contacts ORDER BY id DESC LIMIT 5');
if ($recent) {
    if ($recent->num_rows > 0) {
        echo '<h4>5 pesan kontak terbaru</h4>';
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        echo '<tr><th>ID</th><th>Nama</th><th>Email</th><th>Subjek</th><th>Waktu</th></tr>';
        while ($row = $recent->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['subject']) . '</td>';
            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Tabel contacts masih kosong.</p>';
    }
    $recent->free();
} else {
    echo '<p style="color:red;">Query gagal: ' . htmlspecialchars($conn->error) . '</p>';
}

$conn->close();
