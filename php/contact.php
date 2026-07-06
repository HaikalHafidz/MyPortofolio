<?php
session_start();
require_once __DIR__ . '/config.php';

function isAjaxRequest(): bool
{
    return (
        (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (!empty($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
    );
}

function respond(bool $success, string $message, int $httpCode = 200): void
{
    if (isAjaxRequest()) {
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }

    // Fallback untuk browser tanpa JavaScript
    if ($success) {
        header('Location: ../contact_success.html');
    } else {
        $_SESSION['errors'] = [$message];
        header('Location: ../index.html#contact');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Metode tidak diizinkan.', 405);
}

try {
    $conn = getDbConnection();
} catch (RuntimeException $e) {
    respond(false, 'Koneksi database gagal. Pastikan database "portfolio_db" sudah dibuat (lihat schema.sql) dan kredensial di php/config.php sudah benar.', 500);
}

// Rate limiting sederhana berbasis sesi (maks 5 pesan / 10 menit)
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
    respond(false, 'Terlalu banyak percobaan. Coba lagi dalam beberapa menit.', 429);
}

$_SESSION['contact_count_window']++;

// Honeypot anti-spam (input ini harus kosong)
$honeypot = trim($_POST['website'] ?? '');
if ($honeypot !== '') {
    respond(false, 'Permintaan ditolak.', 400);
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($name === '' || mb_strlen($name) > 100) {
    $errors[] = 'Nama wajib diisi (maks. 100 karakter).';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email tidak valid.';
}
if ($subject === '' || mb_strlen($subject) > 150) {
    $errors[] = 'Subjek wajib diisi (maks. 150 karakter).';
}
if ($message === '' || mb_strlen($message) > 3000) {
    $errors[] = 'Pesan wajib diisi (maks. 3000 karakter).';
}

if (!empty($errors)) {
    respond(false, implode(' ', $errors), 422);
}

$stmt = $conn->prepare(
    'INSERT INTO contacts (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())'
);
$stmt->bind_param('ssss', $name, $email, $subject, $message);

if (!$stmt->execute()) {
    respond(false, 'Gagal menyimpan pesan. Silakan coba lagi.', 500);
}

$stmt->close();
$to = 'haikalhafidz015@gmail.com';
$emailSubject = 'Pesan Baru dari Portofolio: ' . $subject;
$emailBody = "Anda menerima pesan baru dari portofolio Anda.\n\n"
    . "Nama: {$name}\n"
    . "Email: {$email}\n"
    . "Subjek: {$subject}\n"
    . "Pesan:\n{$message}\n\n"
    . 'Waktu: ' . date('Y-m-d H:i:s');

$sent = false;
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    try {
        $mailer = new PHPMailer\PHPMailer\PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = 'smtp.example.com';
        $mailer->SMTPAuth = true;
        $mailer->Username = 'smtp_user@example.com';
        $mailer->Password = 'smtp_password';
        $mailer->SMTPSecure = 'tls';
        $mailer->Port = 587;

        $fromDomain = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $fromEmail = 'noreply@' . preg_replace('/[^a-z0-9.\-]/i', '', $fromDomain);

        $mailer->setFrom($fromEmail, 'Portfolio Contact');
        $mailer->addAddress($to);
        $mailer->addReplyTo($email, $name);
        $mailer->Subject = $emailSubject;
        $mailer->Body = $emailBody;
        $mailer->isHTML(false);

        $sent = $mailer->send();
    } catch (Exception $e) {
        $sent = false;
    }
}

if (!$sent) {
    $fromDomain = $_SERVER['SERVER_NAME'] ?? 'localhost';
    $fromEmail = 'noreply@' . preg_replace('/[^a-z0-9.\-]/i', '', $fromDomain);
    $headers = "From: \"Portfolio Contact\" <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
    @mail($to, $emailSubject, $emailBody, $headers);
}

$conn->close();

respond(true, "Terima kasih, {$name}! Pesan Anda sudah tersimpan dan akan segera dibalas.");
