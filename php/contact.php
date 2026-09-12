<?php
session_start();
require_once __DIR__ . '/config.php';

header('X-Content-Type-Options: nosniff');

function isAjaxRequest(): bool
{
    return (
        (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (!empty($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))
    );
}

function respond(bool $success, string $message, int $httpCode = 200, array $extra = []): void
{
    if (isAjaxRequest()) {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array_merge([
            'success' => $success,
            'message' => $message,
        ], $extra), JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($success) {
        header('Location: ../contact_success.html');
    } else {
        $_SESSION['errors'] = [$message];
        header('Location: ../index.html#contact');
    }
    exit;
}

/** Hilangkan karakter CR/LF agar tidak bisa dipakai untuk header injection */
function sanitizeHeaderValue(string $value): string
{
    return trim(str_replace(["\r", "\n", "%0a", "%0d"], '', $value));
}

function sendWhatsAppNotification(string $message): bool
{
    if (
        !defined('WA_PHONE') || !defined('WA_APIKEY')
        || WA_PHONE === '' || WA_APIKEY === '' || WA_APIKEY === 'YOUR_CALLMEBOT_APIKEY'
    ) {
        return false;
    }

    $url = 'https://api.callmebot.com/whatsapp.php'
        . '?phone=' . urlencode(WA_PHONE)
        . '&text=' . urlencode($message)
        . '&apikey=' . urlencode(WA_APIKEY);

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $result = curl_exec($ch);
        $ok = $result !== false && curl_errno($ch) === 0;
        curl_close($ch);
        if ($ok) {
            return true;
        }
    }

    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create(['http' => ['timeout' => 8]]);
        $result = @file_get_contents($url, false, $context);
        return $result !== false;
    }

    return false;
}

function sendOwnerEmailNotification(string $name, string $email, string $subject, string $message): bool
{
    $safeName = sanitizeHeaderValue($name);
    $safeEmail = sanitizeHeaderValue($email);
    $safeSubject = sanitizeHeaderValue($subject);

    $emailSubject = mb_encode_mimeheader(
        'Pesan Baru dari Portofolio: ' . $safeSubject,
        'UTF-8'
    );

    $emailBody = "Anda menerima pesan baru dari portofolio Anda.\n\n"
        . "Nama: {$safeName}\n"
        . "Email: {$safeEmail}\n"
        . "Subjek: {$safeSubject}\n"
        . "Pesan:\n{$message}\n\n"
        . 'Waktu: ' . date('Y-m-d H:i:s');

    $fromDomain = $_SERVER['SERVER_NAME'] ?? 'localhost';
    $fromEmail = 'noreply@' . preg_replace('/[^a-z0-9.\-]/i', '', $fromDomain);
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'Portfolio Contact';

    $headers = "From: \"{$siteName}\" <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$safeEmail}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

    $to = defined('CONTACT_EMAIL_TO') ? CONTACT_EMAIL_TO : $safeEmail;

    return @mail($to, $emailSubject, $emailBody, $headers);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Metode tidak diizinkan.', 405);
}

try {
    $conn = getDbConnection();
} catch (RuntimeException $e) {
    respond(false, 'Koneksi database gagal. Pastikan database "portfolio_db" sudah dibuat (lihat schema.sql) dan kredensial di php/config.php sudah benar.', 500);
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
    respond(false, 'Terlalu banyak percobaan. Coba lagi dalam beberapa menit.', 429);
}

$_SESSION['contact_count_window']++;

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

$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? null);
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

$stmt = $conn->prepare(
    'INSERT INTO contacts (name, email, subject, message, ip_address, user_agent, created_at)
     VALUES (?, ?, ?, ?, ?, ?, NOW())'
);
$stmt->bind_param('ssssss', $name, $email, $subject, $message, $ipAddress, $userAgent);

if (!$stmt->execute()) {
    respond(false, 'Gagal menyimpan pesan. Silakan coba lagi.', 500);
}

$insertedId = $stmt->insert_id;
$stmt->close();
$conn->close();

$emailSent = sendOwnerEmailNotification($name, $email, $subject, $message);

$waMessage = "📩 Pesan baru dari Portofolio!\n"
    . "Nama: {$name}\n"
    . "Email: {$email}\n"
    . "Subjek: {$subject}\n"
    . "Pesan: {$message}";

$waSent = sendWhatsAppNotification($waMessage);

respond(true, "Terima kasih, {$name}! Pesan Anda sudah tersimpan dan akan segera dibalas.", 200, [
    'id' => $insertedId,
    'notifications' => [
        'email' => $emailSent,
        'whatsapp' => $waSent,
    ],
]);
