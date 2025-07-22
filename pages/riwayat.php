<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
?>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($_SESSION['message']); ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Booking</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/transaksi.css">
  <link rel="stylesheet" href="../assets/css/riwayat.css">
</head>
<body>
  <div class="container">
    <h1>Riwayat Booking</h1>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Layanan</th>
          <th>Tanggal</th>
          <th>Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="riwayat-body">
        <!-- AJAX isi di sini -->
      </tbody>
    </table>

    <p id="no-data" style="display:none; text-align:center; margin-top:20px;">
      Tidak ada riwayat booking.
    </p>

    <div class="back-btn">
      <a href="cari.php">← Kembali Cari Bengkel</a>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    fetch('../ajax/get_riwayat.php')
      .then(res => {
        if (!res.ok) throw new Error('Gagal ambil data');
        return res.json();
      })
      .then(data => {
        const tbody = document.getElementById('riwayat-body');
        if (!data || data.length === 0) {
          document.getElementById('no-data').style.display = 'block';
          return;
        }

        data.forEach(item => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${item.booking_id}</td>
            <td>${item.service_name}</td>
            <td>${new Date(item.booking_date).toLocaleDateString('id-ID')} ${item.booking_time}</td>
            <td>Rp${parseInt(item.price).toLocaleString('id-ID')}</td>
            <td>${item.status}</td>
            <td class="aksi-btn">
              <a href="edit.php?booking_id=${item.booking_id}">Edit</a>|
              <a href="hapus.php?id=${item.booking_id}" onclick="return confirm('Yakin ingin menghapus booking ini?')">Hapus</a>
            </td>
          `;
          tbody.appendChild(tr);
        });
      })
      .catch(err => {
        console.error(err);
        const noData = document.getElementById('no-data');
        noData.textContent = 'Gagal memuat data.';
        noData.style.display = 'block';
      });
  });
  </script>
</body>
</html>
