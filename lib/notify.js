const nodemailer = require('nodemailer');
const config = require('../config');

function cleanHeader(value) {
  return String(value).replace(/[\r\n]/g, '').trim();
}

function escapeHtml(text) {
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

async function sendOwnerEmail({ name, email, subject, message }) {
  const { smtp, contactTo, siteName } = config;
  if (!smtp.user || !smtp.pass || !contactTo) return false;

  const safeName = cleanHeader(name);
  const safeEmail = cleanHeader(email);
  const safeSubject = cleanHeader(subject);

  const text =
    'Anda menerima pesan baru dari form kontak portofolio Anda.\n\n' +
    `Nama   : ${safeName}\n` +
    `Email  : ${safeEmail}\n` +
    `Subjek : ${safeSubject}\n` +
    `Pesan  :\n${message}\n\n` +
    `Waktu  : ${new Date().toISOString()}`;

  try {
    const transporter = nodemailer.createTransport({
      host: smtp.host,
      port: smtp.port,
      secure: smtp.secure,
      requireTLS: !smtp.secure,
      auth: { user: smtp.user, pass: smtp.pass },
    });

    await transporter.sendMail({
      from: { name: smtp.fromName || siteName, address: smtp.user },
      to: contactTo,
      replyTo: { name: safeName, address: safeEmail },
      subject: `Pesan Baru dari Portofolio: ${safeSubject}`,
      text,
      html: escapeHtml(text).replace(/\n/g, '<br>'),
    });
    return true;
  } catch (err) {
    console.error('Gagal mengirim email notifikasi:', err.message);
    return false;
  }
}

async function sendWhatsApp(message) {
  const { phone, apikey } = config.wa;
  if (!phone || !apikey) return false;

  const url =
    'https://api.callmebot.com/whatsapp.php' +
    `?phone=${encodeURIComponent(phone)}` +
    `&text=${encodeURIComponent(message)}` +
    `&apikey=${encodeURIComponent(apikey)}`;

  try {
    const res = await fetch(url, { signal: AbortSignal.timeout(8000) });
    return res.ok;
  } catch (err) {
    console.error('Gagal mengirim WhatsApp:', err.message);
    return false;
  }
}

module.exports = { sendOwnerEmail, sendWhatsApp };
