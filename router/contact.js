const express = require('express');
const multer = require('multer');
const rateLimit = require('express-rate-limit');
const db = require('../lib/db');
const { sendOwnerEmail, sendWhatsApp } = require('../lib/notify');

const router = express.Router();
const parseMultipart = multer().none(); // form dari FormData dikirim sebagai multipart

const limiter = rateLimit({
  windowMs: 10 * 60 * 1000,
  limit: 5,
  standardHeaders: true,
  legacyHeaders: false,
  handler: (req, res) => reply(req, res, false, 'Terlalu banyak percobaan. Coba lagi dalam beberapa menit.', 429),
});

const text = (value) => String(value ?? '').trim();
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

/** JSON untuk fetch/AJAX, redirect untuk form HTML biasa */
function reply(req, res, success, message, status = 200, extra = {}) {
  const wantsJson = req.xhr || (req.get('accept') || '').includes('application/json');
  if (wantsJson) {
    return res.status(status).json({ success, message, ...extra });
  }
  return res.redirect(303, success ? '/contact_success.html' : '/#contact');
}

router.post('/', limiter, parseMultipart, async (req, res) => {
  const body = req.body || {};

  // Honeypot anti-spam: harus selalu kosong
  if (text(body.website) !== '' || text(body._gotcha) !== '') {
    return reply(req, res, false, 'Permintaan ditolak.', 400);
  }

  const name = text(body.name);
  const email = text(body.email);
  const subject = text(body.subject);
  const message = text(body.message);

  const errors = [];
  if (!name || name.length > 100) errors.push('Nama wajib diisi (maks. 100 karakter).');
  if (!email || !EMAIL_RE.test(email)) errors.push('Email tidak valid.');
  if (!subject || subject.length > 150) errors.push('Subjek wajib diisi (maks. 150 karakter).');
  if (!message || message.length > 3000) errors.push('Pesan wajib diisi (maks. 3000 karakter).');
  if (errors.length) return reply(req, res, false, errors.join(' '), 422);

  let insertId;
  try {
    const [result] = await db.execute(
      `INSERT INTO contacts (name, email, subject, message, ip_address, user_agent, created_at)
       VALUES (?, ?, ?, ?, ?, ?, NOW())`,
      [name, email, subject, message, req.ip || null, (req.get('user-agent') || '').slice(0, 255)]
    );
    insertId = result.insertId;
  } catch (err) {
    console.error('Gagal menyimpan pesan:', err.message);
    return reply(req, res, false, 'Gagal menyimpan pesan. Pastikan database sudah dibuat (lihat schema.sql) dan kredensial di .env benar.', 500);
  }

  const [emailSent, waSent] = await Promise.all([
    sendOwnerEmail({ name, email, subject, message }),
    sendWhatsApp(`📩 Pesan baru dari Portofolio!\nNama: ${name}\nEmail: ${email}\nSubjek: ${subject}\nPesan: ${message}`),
  ]);

  return reply(req, res, true, `Terima kasih, ${name}! Pesan Anda sudah tersimpan dan akan segera dibalas.`, 200, {
    id: insertId,
    notifications: { email: emailSent, whatsapp: waSent },
  });
});

module.exports = router;
