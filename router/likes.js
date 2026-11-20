const crypto = require('crypto');
const express = require('express');
const db = require('../lib/db');
const config = require('../config');

const router = express.Router();
const PROJECTS = ['unindra', 'pondok', 'vidioon'];

// Satu pengunjung (hash IP + browser) hanya dihitung satu like per proyek
function voterId(req) {
  return crypto
    .createHash('sha256')
    .update(`${req.ip || ''}${req.get('user-agent') || ''}${config.likeSalt}`)
    .digest('hex');
}

async function snapshot(voter) {
  const counts = Object.fromEntries(PROJECTS.map((p) => [p, 0]));
  const [rows] = await db.query('SELECT project, COUNT(*) AS total FROM project_likes GROUP BY project');
  rows.forEach((r) => {
    if (r.project in counts) counts[r.project] = Number(r.total);
  });

  const [mineRows] = await db.execute('SELECT project FROM project_likes WHERE voter = ?', [voter]);
  return { counts, mine: mineRows.map((r) => r.project) };
}

router.get('/', async (req, res) => {
  try {
    res.json(await snapshot(voterId(req)));
  } catch (err) {
    console.error('likes GET:', err.message);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
});

router.post('/', async (req, res) => {
  const { project, like } = req.body || {};
  if (!PROJECTS.includes(project)) {
    return res.status(400).json({ error: 'Proyek tidak valid' });
  }

  try {
    const voter = voterId(req);
    if (like) {
      await db.execute('INSERT IGNORE INTO project_likes (project, voter) VALUES (?, ?)', [project, voter]);
    } else {
      await db.execute('DELETE FROM project_likes WHERE project = ? AND voter = ?', [project, voter]);
    }
    res.json(await snapshot(voter));
  } catch (err) {
    console.error('likes POST:', err.message);
    res.status(500).json({ error: 'Terjadi kesalahan server' });
  }
});

module.exports = router;
