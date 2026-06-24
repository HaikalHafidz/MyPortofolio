<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Donasi - Haikal Hafidz</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
            color: #ffffff;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Variables */
        :root {
            --primary-color: #6c63ff;
            --dark-card: rgba(30, 30, 40, 0.95);
            --border-color: rgba(255, 255, 255, 0.1);
            --dark-text: #ffffff;
        }

        /* Header & Navigation */
        header {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo h2 a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s;
            font-weight: 500;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .burger {
            display: none;
            cursor: pointer;
        }

        .burger div {
            width: 25px;
            height: 3px;
            background-color: #ffffff;
            margin: 5px;
            transition: all 0.3s ease;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Konfirmasi Container */
        .konfirmasi-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 2rem;
            background-color: var(--dark-card);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #6c63ff, #ff6584);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .success-icon {
            text-align: center;
            font-size: 4rem;
            color: #4CAF50;
            margin-bottom: 1rem;
        }

        .donasi-detail {
            background: rgba(108, 99, 255, 0.1);
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            border: 1px solid rgba(108, 99, 255, 0.3);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .detail-value {
            font-weight: 500;
        }

        .payment-instructions {
            background: rgba(255, 193, 7, 0.1);
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .payment-instructions h3 {
            color: #ffc107;
            margin-bottom: 1rem;
        }

        .payment-steps {
            list-style: none;
            padding: 0;
        }

        .payment-steps li {
            margin-bottom: 0.8rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .payment-steps li::before {
            content: "✓";
            color: #4CAF50;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        .bank-details {
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            font-family: monospace;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-primary,
        .btn-secondary {
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            font-family: 'Poppins', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6c63ff, #5a52e0);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(108, 99, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: var(--primary-color);
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: #8b84ff;
            transform: translateX(-5px);
        }

        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.8);
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        .social-links {
            margin-top: 1rem;
        }

        .social-links a {
            color: #ffffff;
            margin: 0 0.5rem;
            font-size: 1.5rem;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .nav-links {
                position: fixed;
                right: -100%;
                top: 70px;
                height: calc(100vh - 70px);
                background: rgba(0, 0, 0, 0.95);
                flex-direction: column;
                width: 100%;
                text-align: center;
                transition: 0.5s;
                padding-top: 2rem;
            }

            .nav-links.active {
                right: 0;
            }

            .burger {
                display: block;
            }

            .konfirmasi-container {
                margin: 80px 1rem 30px;
                padding: 1.5rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .btn-group {
                flex-direction: column;
            }

            .detail-row {
                flex-direction: column;
                gap: 0.3rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <h2><a href="index.html" style="color: var(--primary-color); text-decoration: none;">Haikal Hafidz</a>
                </h2>
            </div>
            <ul class="nav-links">
                <li><a href="index.html">Beranda</a></li>
                <li><a href="index.html#about">Tentang</a></li>
                <li><a href="index.html#education">Pendidikan</a></li>
                <li><a href="index.html#experience">Pengalaman</a></li>
                <li><a href="index.html#skills">Keahlian</a></li>
                <li><a href="donate.html">Donasi</a></li>
                <!-- Riwayat Donasi disembunyikan — notifikasi lewat WhatsApp -->
            </ul>
            <div class="burger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="konfirmasi-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="section-title">Donasi Berhasil Dicatat!</h2>
            <p style="text-align: center; margin-bottom: 2rem; color: rgba(255,255,255,0.8);">
                Terima kasih atas dukungan Anda! Donasi telah berhasil dicatat dalam sistem.
            </p>

            <?php
            // Ambil data dari URL parameters
            $kode = isset($_GET['kode']) ? htmlspecialchars($_GET['kode']) : '';
            $nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : '';
            $email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
            $jumlah = isset($_GET['jumlah']) ? htmlspecialchars($_GET['jumlah']) : '';
            $metode = isset($_GET['metode']) ? htmlspecialchars($_GET['metode']) : '';
            $opsi_bank = isset($_GET['opsi_bank']) ? htmlspecialchars($_GET['opsi_bank']) : '';
            $opsi_ewallet = isset($_GET['opsi_ewallet']) ? htmlspecialchars($_GET['opsi_ewallet']) : '';

            if (empty($kode) || empty($nama) || empty($email) || empty($jumlah) || empty($metode)) {
                echo '<div style="text-align: center; color: #F44336; padding: 2rem;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem;"></i>
                        <h3>Data donasi tidak lengkap</h3>
                        <p>Silakan kembali ke halaman donasi dan coba lagi.</p>
                        <a href="../donate.html" class="btn-primary" style="margin-top: 1rem; display: inline-block;">
                            <i class="fas fa-arrow-left"></i> Kembali ke Donasi
                        </a>
                      </div>';
                exit();
            }
            ?>

            <div class="donasi-detail">
                <h3 style="color: var(--primary-color); margin-bottom: 1rem;"><i class="fas fa-receipt"></i> Detail
                    Donasi</h3>
                <div class="detail-row">
                    <span class="detail-label">Kode Donasi:</span>
                    <span class="detail-value">
                        <?php echo $kode; ?> <button onclick="copyKode()"
                            style="background: none; border: none; color: var(--primary-color); cursor: pointer; margin-left: 0.5rem;"><i
                                class="fas fa-copy"></i></button>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Donatur:</span>
                    <span class="detail-value">
                        <?php echo $nama; ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">
                        <?php echo $email; ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jumlah Donasi:</span>
                    <span class="detail-value">Rp
                        <?php echo number_format($jumlah, 0, ',', '.'); ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Metode Pembayaran:</span>
                    <span class="detail-value">
                        <?php
                        if ($metode === 'transfer_bank') {
                            echo 'Transfer Bank';
                            if ($opsi_bank) echo ' - ' . strtoupper($opsi_bank);
                        } else if ($metode === 'e_wallet') {
                            echo 'E-Wallet';
                            if ($opsi_ewallet) echo ' - ' . ucfirst($opsi_ewallet);
                        } else if ($metode === 'qris') {
                            echo 'QRIS';
                        } else {
                            echo $metode;
                        }
                        ?>
                    </span>
                </div>
            </div>

            <div class="payment-instructions">
                <h3><i class="fas fa-info-circle"></i> Instruksi Pembayaran</h3>
                <p style="margin-bottom: 1rem;">Untuk menyelesaikan donasi, silakan transfer ke rekening berikut:</p>

                <div class="bank-details">
                    <strong>Bank BCA</strong><br>
                    No. Rekening: 1234567890<br>
                    Atas Nama: Muhammad Haikal Hafidz<br>
                    <br>
                    <strong>Jumlah Transfer:</strong> Rp
                    <?php echo number_format($jumlah, 0, ',', '.'); ?><br>
                    <strong>Kode Donasi:</strong>
                    <?php echo $kode; ?> (cantumkan di berita transfer)
                </div>

                <ol class="payment-steps">
                    <li>Buka aplikasi banking atau e-wallet Anda</li>
                    <li>Transfer sesuai jumlah di atas</li>
                    <li>Cantumkan kode donasi (
                        <?php echo $kode; ?>) di berita transfer
                    </li>
                    <li>Konfirmasi pembayaran akan diproses dalam 1-2 hari kerja</li>
                    <li>Status donasi akan diperbarui otomatis setelah konfirmasi</li>
                </ol>

                <p style="color: #ffc107; font-size: 0.9rem; margin-top: 1rem;">
                    <i class="fas fa-clock"></i> <strong>Catatan:</strong> Sistem ini adalah simulasi. Untuk
                    implementasi nyata, integrasikan dengan payment gateway seperti Midtrans atau Stripe.
                </p>
            </div>

            <div class="btn-group">
                <button onclick="sendWaNotification('Dicatat')" class="btn-primary">
                    <i class="fas fa-bell"></i> Kirim Notifikasi WA
                </button>
                <button onclick="confirmPayment()" class="btn-secondary">
                    <i class="fas fa-check"></i> Konfirmasi Pembayaran Selesai
                </button>
            </div>

            <a href="../index.html" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Haikal Hafidz. All rights reserved.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="https://github.com/HaikalHafidz" target="_blank"><i class="fab fa-github"></i></a>
                <a href="https://www.instagram.com/ecaou_?igsh=M3ZvNWV5bDlwejB3" target="_blank"><i
                        class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Navigation Toggle
        const burger = document.querySelector('.burger');
        const navLinks = document.querySelector('.nav-links');

        burger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            burger.classList.toggle('toggle');
        });

        // Copy kode donasi ke clipboard
        function copyKode() {
            const kode = "<?php echo $kode; ?>";
            navigator.clipboard.writeText(kode).then(() => {
                alert('Kode donasi berhasil disalin: ' + kode);
            }).catch(() => {
                // Fallback untuk browser yang tidak mendukung clipboard API
                const textArea = document.createElement('textarea');
                textArea.value = kode;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Kode donasi berhasil disalin: ' + kode);
            });
        }

        // Kirim pesan WhatsApp ke pemilik (nomor internasional 62...)
        function openWhatsAppWithText(number, text) {
            const cleanNumber = number.replace(/[^0-9]/g, '');
            const url = 'https://wa.me/' + cleanNumber + '?text=' + encodeURIComponent(text);
            window.open(url, '_blank');
        }

        function sendWaNotification(initialStatus = 'Dicatat') {
            const kode = "<?php echo $kode; ?>";
            const nama = "<?php echo $nama; ?>";
            const jumlah = "<?php echo number_format($jumlah, 0, ',', '.'); ?>";
            const metode = "<?php echo $metode; ?>";
            const number = '62895433210605';

            const message = `Notifikasi Donasi - Status: ${initialStatus}%0AKode: ${kode}%0ANama: ${nama}%0AJumlah: Rp ${jumlah}%0AMetode: ${metode}`;
            // open WA
            openWhatsAppWithText(number, message);
        }

        // Konfirmasi pembayaran selesai: update server then notify via WhatsApp
        async function confirmPayment() {
            if (!confirm('Apakah Anda sudah melakukan pembayaran? Status donasi akan diubah menjadi "Berhasil".')) return;

            const kode = "<?php echo $kode; ?>";
            try {
                const response = await fetch('update_donasi_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'kode=' + encodeURIComponent(kode) + '&status=success'
                });
                const result = await response.json();
                const number = '62895433210605';

                if (result.success) {
                    alert('Status donasi berhasil diperbarui menjadi "Berhasil"! Notifikasi WA akan terbuka.');
                    const message = `Notifikasi Donasi - Status: Berhasil\nKode: ${kode}\nWaktu: ${new Date().toLocaleString()}`;
                    openWhatsAppWithText(number, message);
                } else {
                    alert('Gagal update status: ' + result.error + '\nMembuka WhatsApp untuk notifikasi manual.');
                    const message = `Notifikasi Donasi - Status: Gagal update sistem\nKode: ${kode}\nError: ${result.error}`;
                    openWhatsAppWithText(number, message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message + '\nMembuka WhatsApp untuk notifikasi manual.');
                const message = `Notifikasi Donasi - Status: Error jaringan\nKode: ${kode}\nError: ${error.message}`;
                openWhatsAppWithText('62895433210605', message);
            }
        }
    </script>
</body>

</html>