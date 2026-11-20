const path = require('path');
const express = require('express');
const config = require('./config');

const app = express();
app.set('trust proxy', config.trustProxy);
app.disable('x-powered-by');

app.use((req, res, next) => {
  res.set('X-Content-Type-Options', 'nosniff');
  next();
});

app.use('/api', (req, res, next) => {
  res.set({
    'Access-Control-Allow-Origin': config.corsOrigin,
    'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type, X-Requested-With',
  });
  if (req.method === 'OPTIONS') return res.sendStatus(204);
  next();
});
app.use('/api', express.json({ limit: '20kb' }), express.urlencoded({ extended: false, limit: '20kb' }));
app.use('/api/contact', require('./routes/contact'));
app.use('/api/likes', require('./routes/likes'));

['css', 'js', 'img', 'cv'].forEach((dir) => {
  app.use(`/${dir}`, express.static(path.join(__dirname, dir)));
});

const pages = {
  '/': 'index.html',
  '/index.html': 'index.html',
  '/team.html': 'team.html',
  '/contact_success.html': 'contact_success.html',
};
Object.entries(pages).forEach(([route, file]) => {
  app.get(route, (req, res) => res.sendFile(path.join(__dirname, file)));
});

app.use((req, res) => res.status(404).send('Halaman tidak ditemukan'));

app.listen(config.port, () => {
  console.log(`Server berjalan di http://localhost:${config.port}`);
  if (!config.smtp.user || !config.smtp.pass) {
    console.warn('SMTP belum diisi di .env: notifikasi email dilewati.');
  }
});
