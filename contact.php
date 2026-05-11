<?php
// Mulai session di awal
session_start();

// Konfigurasi database
$servername = "localhost";
$username = "portfolio_user";
$password = "password";
$dbname = "portfolio_db";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    $_SESSION['errors'] = ["Koneksi database gagal. Silakan coba lagi nanti."];
    header("Location: index.html#contact");
    exit();
}

// Memproses form jika data dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Membersihkan dan mengambil data dari form
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Validasi data
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Nama harus diisi";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email harus valid";
    }
    
    if (empty($subject)) {
        $errors[] = "Subjek harus diisi";
    }
    
    if (empty($message)) {
        $errors[] = "Pesan harus diisi";
    }
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $sql = "INSERT INTO contacts (name, email, subject, message, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            // Kirim email notifikasi (optional)
            $to = "haikalhafidz015@gmail.com";
            $email_subject = "Pesan Baru dari Portofolio: " . $subject;
            $email_body = "Anda menerima pesan baru dari portofolio Anda.\n\n" .
                         "Nama: $name\n" .
                         "Email: $email\n" .
                         "Subjek: $subject\n" .
                         "Pesan:\n$message\n\n" .
                         "Waktu: " . date('Y-m-d H:i:s');
            $headers = "From: " . $email . "\r\n" .
                      "Reply-To: " . $email . "\r\n" .
                      "X-Mailer: PHP/" . phpversion();
            
            // Uncomment untuk mengirim email
            // mail($to, $email_subject, $email_body, $headers);
            
            // Redirect ke halaman sukses
            header("Location: contact_success.html");
            exit();
        } else {
            $errors[] = "Terjadi kesalahan. Silakan coba lagi.";
        }
        
        $stmt->close();
    }
    
    // Jika ada error, simpan di session untuk ditampilkan
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    
    // Redirect kembali ke form
    header("Location: index.html#contact");
    exit();
}

$conn->close();
?>