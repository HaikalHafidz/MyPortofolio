DROP DATABASE IF EXISTS portfolio_db;
CREATE DATABASE portfolio_db;
USE portfolio_db;

CREATE USER IF NOT EXISTS 'portfolio_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON portfolio_db.* TO 'portfolio_user'@'localhost';
FLUSH PRIVILEGES;

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS donasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_donasi VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    pesan TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    tanggal DATETIME NOT NULL
);

INSERT INTO donasi (kode_donasi, nama, email, jumlah, metode_pembayaran, pesan, status, tanggal) VALUES
('DON2024123456', 'John Doe', 'john@example.com', 50000.00, 'transfer_bank', 'Semangat terus untuk studinya!', 'success', NOW()),
('DON2024123457', 'Jane Smith', 'jane@example.com', 100000.00, 'e_wallet', 'Sukses selalu!', 'success', NOW());