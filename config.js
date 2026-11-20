require('dotenv').config();

const env = process.env;
const siteName = env.SITE_NAME || 'Muhammad Haikal Hafidz - Portfolio';

module.exports = {
  port: Number(env.PORT) || 3000,
  siteName,
  db: {
    host: env.DB_HOST || 'localhost',
    user: env.DB_USER || 'root',
    password: env.DB_PASS || '',
    database: env.DB_NAME || 'portfolio_db',
  },
  contactTo: env.CONTACT_EMAIL_TO || 'haikalhafidz015@gmail.com',
  smtp: {
    host: env.SMTP_HOST || 'smtp.gmail.com',
    port: Number(env.SMTP_PORT) || 587,
    secure: env.SMTP_SECURE === 'ssl',
    user: env.SMTP_USERNAME || '',
    pass: env.SMTP_PASSWORD || '',
    fromName: env.SMTP_FROM_NAME || siteName,
  },
  wa: {
    phone: env.WA_PHONE || '62895433210605',
    apikey: env.WA_APIKEY || '',
  },
  likeSalt: env.LIKE_SALT || 'ganti-salt-ini',
  corsOrigin: env.CORS_ORIGIN || '*',
  trustProxy: env.TRUST_PROXY === 'true',
};
