<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include file koneksi database
require_once '../includes/db.php';

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Booking</title>
  <link rel="stylesheet" href="../assets/css/transaksi.css">
</head>
<body>
  <div class="form-container">
    <h2>Form Transaksi</h2>
    <form action="booking.php" method="POST" id="bookingForm">
      
      <!-- Pilih Bengkel -->
      <div class="form-group">
        <label for="workshopSelect">Pilih Bengkel</label>
        <select name="workshop_id" id="workshopSelect" required>
          <option value="">-- Pilih Bengkel --</option>
          <?php
            $queryBengkel = "SELECT workshop_id, name FROM bengkel WHERE status = 'active'";
            $resultBengkel = mysqli_query($conn, $queryBengkel);
            
            if(mysqli_num_rows($resultBengkel) > 0){
              while($row = mysqli_fetch_assoc($resultBengkel)){
                echo "<option value='".htmlspecialchars($row['workshop_id'])."'>".htmlspecialchars($row['name'])."</option>";
              }
            } else {
              echo "<option value='' disabled>Tidak ada bengkel tersedia</option>";
            }
          ?>
        </select>
      </div>

      <!-- Pilih Service -->
      <div class="form-group">
        <label for="serviceSelect">Pilih Layanan</label>
        <select name="service_id" id="serviceSelect" required disabled>
          <option value="">-- Pilih bengkel terlebih dahulu --</option>
        </select>
      </div>

      <!-- Tanggal + Waktu -->
      <div class="form-row">
        <div class="form-group">
          <label for="bookingDate">Tanggal Booking</label>
          <input type="date" name="booking_date" id="bookingDate" required min="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-group">
          <label for="bookingTime">Waktu Booking</label>
          <input type="time" name="booking_time" id="bookingTime" required min="08:00" max="17:00">
        </div>
      </div>

      <!-- Informasi Pelanggan -->
      <div class="form-group">
        <label for="customerName">Nama Pelanggan</label>
        <input type="text" name="customer_name" id="customerName" required>
      </div>

      <div class="form-group">
        <label for="customerPhone">Nomor Telepon</label>
        <input type="tel" name="customer_phone" id="customerPhone" required>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="submit-btn">Buat Booking</button>

      <p class="info">Lihat <a href="riwayat.php">Riwayat Transaksi</a></p>
    </form>
  </div>

  <!-- AJAX Script -->
  <script>
  document.addEventListener("DOMContentLoaded", function() {
      const workshopSelect = document.getElementById("workshopSelect");
      const serviceSelect = document.getElementById("serviceSelect");
      const bookingForm = document.getElementById("bookingForm");
      
      // Format tanggal default ke hari ini
      document.getElementById('bookingDate').valueAsDate = new Date();

      // Handle perubahan bengkel
      workshopSelect.addEventListener("change", function() {
          const workshopId = this.value;
          
          if (!workshopId) {
              serviceSelect.innerHTML = '<option value="">-- Pilih bengkel terlebih dahulu --</option>';
              serviceSelect.disabled = true;
              return;
          }
          
          serviceSelect.innerHTML = '<option value="">Memuat layanan...</option>';
          serviceSelect.disabled = false;
          
          fetch('get_services.php?workshop_id=' + workshopId)
              .then(response => {
                  if (!response.ok) {
                      throw new Error('Network response was not ok');
                  }
                  return response.json();
              })
              .then(data => {
                  if (data && data.length > 0) {
                      let options = '<option value="">-- Pilih Layanan --</option>';
                      data.forEach(service => {
                          options += `<option value="${service.service_id}">${service.service_name} - Rp ${service.price.toLocaleString()}</option>`;
                      });
                      serviceSelect.innerHTML = options;
                  } else {
                      serviceSelect.innerHTML = '<option value="">Tidak ada layanan tersedia</option>';
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  serviceSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
              });
      });

      // Validasi form sebelum submit
      bookingForm.addEventListener("submit", function(e) {
          const selectedDate = new Date(document.getElementById('bookingDate').value);
          const today = new Date();
          today.setHours(0, 0, 0, 0);
          
          if (selectedDate < today) {
              e.preventDefault();
              alert('Tanggal booking tidak boleh di masa lalu');
              return false;
          }
          
          if (!serviceSelect.value) {
              e.preventDefault();
              alert('Silakan pilih layanan');
              return false;
          }
          
          return true;
      });
  });
  </script>
</body>
</html>