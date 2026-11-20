// Pengganti php/db_test.php. Jalankan: npm run db:test
const db = require('../lib/db');
const config = require('../config');

(async () => {
  try {
    await db.query('SELECT 1');
    console.log(`Berhasil terhubung ke database: ${config.db.database}`);

    for (const table of ['contacts', 'project_likes']) {
      const [found] = await db.query('SHOW TABLES LIKE ?', [table]);
      if (!found.length) {
        console.log(`Tabel ${table} tidak ditemukan. Jalankan schema.sql terlebih dahulu.`);
        continue;
      }
      const [[row]] = await db.query(`SELECT COUNT(*) AS total FROM \`${table}\``);
      console.log(`Tabel ${table} ditemukan, jumlah baris: ${row.total}`);
    }

    const [recent] = await db.query('SELECT id, name, email, subject, created_at FROM contacts ORDER BY id DESC LIMIT 5');
    if (recent.length) {
      console.log('\n5 pesan kontak terbaru:');
      console.table(recent);
    }
  } catch (err) {
    console.error('Koneksi gagal:', err.message);
    process.exitCode = 1;
  } finally {
    await db.end();
  }
})();
