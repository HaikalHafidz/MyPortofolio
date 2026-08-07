<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); define('DB_PASS', '');
define('DB_NAME', 'portfolio_db');
define('WA_PHONE', '62895433210605');
define('WA_APIKEY', 'YOUR_CALLMEBOT_APIKEY');
function getDbConnection(): mysqli
{
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new RuntimeException('Koneksi database gagal: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
