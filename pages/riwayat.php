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
          <th>Nama Layanan</th>
          <th>Tanggal</th>
          <th>Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="riwayat-body"></tbody>
    </table>

    <p id="no-data" style="display:none; text-align:center; margin-top:20px;">
      Tidak ada riwayat booking.
    </p>

    <div class="back-btn">
      <a href="cari.php">← Kembali Cari Bengkel</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const tbody = document.getElementById('riwayat-body');
      const noData = document.getElementById('no-data');

      try {
        const res = await fetch('../ajax/get_riwayat.php');
        if (!res.ok) throw new Error('Gagal ambil data');

        const data = await res.json();

        if (!data || data.length === 0) {
          noData.style.display = 'block';
          return;
        }

        data.forEach(item => {
          const tr = document.createElement('tr');

          const tanggalBooking = new Date(item.booking_date).toLocaleDateString('id-ID') + ' ' + item.booking_time;
          const hargaFormatted = 'Rp' + parseInt(item.price).toLocaleString('id-ID');

          tr.innerHTML = `
            <td>${item.service_name}</td>
            <td>${tanggalBooking}</td>
            <td>${hargaFormatted}</td>
            <td>${item.status}</td>
            <td class="aksi-btn">
              <a href="edit.php?booking_id=${item.booking_id}" class="btn-edit">Edit</a> |
              <a href="hapus.php?id=${item.booking_id}" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus booking ini? ${item.service_name} pada ${tanggalBooking}')">Hapus</a>
            </td>
          `;

          tbody.appendChild(tr);
        });
      } catch (err) {
        console.error(err);
        noData.textContent = 'Gagal memuat data.';
        noData.style.display = 'block';
      }
    });
  </script>
</body>
</html>
