<?php
session_start();

$servername = "localhost";
$username = "portfolio_user";
$password = "password";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $_SESSION['errors'] = ["Koneksi database gagal. Silakan coba lagi nanti."];
    header("Location: ../index.html#contact");
    exit();
}

if (!isset($_SESSION['contact_window_start'])) {
    $_SESSION['contact_window_start'] = time();
    $_SESSION['contact_count_window'] = 0;
}

$now = time();
$windowSeconds = 10 * 60;
$maxRequests = 5;

if (($now - $_SESSION['contact_window_start']) > $windowSeconds) {
    $_SESSION['contact_window_start'] = $now;
    $_SESSION['contact_count_window'] = 0;
}

if ($_SESSION['contact_count_window'] >= $maxRequests) {
    $_SESSION['errors'] = ["Terlalu banyak percobaan. Coba lagi nanti." ];
    header("Location: ../index.html#contact");
    exit();
}

// Catat request
$_SESSION['contact_count_window']++;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Honeypot anti-spam (input ini harus kosong)
    $honeypot = trim($_POST['website'] ?? '');
    if (!empty($honeypot)) {
        $_SESSION['errors'] = ["Permintaan ditolak (terindikasi spam)."];
        header("Location: ../index.html#contact");
        exit();
    }

    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

    $errors = [];

    if ($name === '') {
        $errors[] = "Nama harus diisi";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email harus valid";
    }

    if ($subject === '') {
        $errors[] = "Subjek harus diisi";
    }

    if ($message === '') {
        $errors[] = "Pesan harus diisi";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: ../index.html#contact");
        exit();
    }

    $sql = "INSERT INTO contacts (name, email, subject, message, created_at) 
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $to = "haikalhafidz015@gmail.com";
        $email_subject = "Pesan Baru dari Portofolio: " . $subject;
        $email_body = "Anda menerima pesan baru dari portofolio Anda.\n\n" .
            "Nama: {$name}\n" .
            "Email: {$email}\n" .
            "Subjek: {$subject}\n" .
            "Pesan:\n{$message}\n\n" .
            "Waktu: " . date('Y-m-d H:i:s');

        $sent = false;
        if (file_exists(__DIR__ . '/vendor/autoload.php')) {
            require __DIR__ . '/vendor/autoload.php';
            try {
                $phpmailer = new PHPMailer\\PHPMailer\\PHPMailer(true);

                $useSMTP = true; 
                $smtpHost = 'smtp.example.com';
                $smtpUser = 'smtp_user@example.com';
                $smtpPass = 'smtp_password';
                $smtpPort = 587;
                $smtpSecure = 'tls'; 

                if ($useSMTP) {
                    $phpmailer->isSMTP();
                    $phpmailer->Host = $smtpHost;
                    $phpmailer->SMTPAuth = true;
                    $phpmailer->Username = $smtpUser;
                    $phpmailer->Password = $smtpPass;
                    $phpmailer->SMTPSecure = $smtpSecure;
                    $phpmailer->Port = $smtpPort;

                    $phpmailer->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ];
                }

                $fromDomain = $_SERVER['SERVER_NAME'] ?? 'localhost';
                $fromEmail = 'noreply@' . preg_replace('/[^a-z0-9\\.\\-]/i', '', $fromDomain);

                $phpmailer->setFrom($fromEmail, 'Portfolio Contact');
                $phpmailer->addAddress($to);
                $phpmailer->addReplyTo($email, $name);

                $phpmailer->Subject = $email_subject;
                $phpmailer->Body = $email_body;
                $phpmailer->isHTML(false);

                $sent = $phpmailer->send();
            } catch (Exception $e) {
                $sent = false;
            }
        }

        if (!$sent) {
            $fromDomain = $_SERVER['SERVER_NAME'] ?? 'localhost';
            $fromEmail = 'noreply@' . preg_replace('/[^a-z0-9\\.\\-]/i', '', $fromDomain);
            $headers = "From: \"Portfolio Contact\" <{$fromEmail}>\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

            @mail($to, $email_subject, $email_body, $headers);
        }

        header("Location: ../contact_success.html");
        exit();
    }

    $_SESSION['errors'] = ["Terjadi kesalahan. Silakan coba lagi."];
    $_SESSION['form_data'] = $_POST;
    header("Location: ../index.html#contact");
    exit();
}

$conn->close();
?>
