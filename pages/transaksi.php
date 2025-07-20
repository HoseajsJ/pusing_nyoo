<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Booking</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2, h3 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        form {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        input[type="number"], input[type="text"], input[type="date"], input[type="time"], select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button, input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 5px;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #45a049;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        .btn-info {
            background-color: #2196F3;
        }
        .btn-info:hover {
            background-color: #0b7dda;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .status-pending {
            color: #FFA500;
            font-weight: bold;
        }
        .status-confirmed {
            color: #4CAF50;
            font-weight: bold;
        }
        .status-canceled {
            color: #f44336;
            font-weight: bold;
        }
        .status-completed {
            color: #2196F3;
            font-weight: bold;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Transaksi Booking</h2>
        <table id="tabelTransaksi">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Workshop ID</th>
                    <th>Service ID</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data transaksi akan dimuat di sini -->
            </tbody>
        </table>

        <h3>Tambah/Edit Booking</h3>
        <form id="formBooking">
            <input type="hidden" id="booking_id" name="booking_id">
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" required>
            </div>
            
            <div class="form-group">
                <label for="workshop_id">Workshop ID:</label>
                <input type="number" id="workshop_id" name="workshop_id" required>
            </div>
            
            <div class="form-group">
                <label for="service_id">Service ID:</label>
                <input type="number" id="service_id" name="service_id" required>
            </div>
            
            <div class="form-group">
                <label for="booking_date">Tanggal Booking:</label>
                <input type="date" id="booking_date" name="booking_date" required>
            </div>
            
            <div class="form-group">
                <label for="booking_time">Waktu Booking:</label>
                <input type="time" id="booking_time" name="booking_time" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="canceled">Canceled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            
            <input type="submit" id="btnSubmit" value="Tambah Booking">
            <button type="button" id="btnCancel" style="display:none;">Batal</button>
        </form>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Konfirmasi Penghapusan</h3>
            <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
            <input type="hidden" id="delete_id">
            <button id="confirmDelete">Hapus</button>
            <button id="cancelDelete">Batal</button>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Memuat data transaksi saat halaman dimuat
        loadBookings();

        // Submit form untuk menambah/mengupdate transaksi
        $('#formBooking').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            var method = ($('#booking_id').val() == '') ? 'POST' : 'PUT';
            var url = ($('#booking_id').val() == '') ? 'api/bookings.php' : 'api/bookings.php?id=' + $('#booking_id').val();
            
            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function(response) {
                    alert(response.message);
                    resetForm();
                    loadBookings();
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        });

        // Tombol batal edit
        $('#btnCancel').click(function() {
            resetForm();
        });

        // Modal konfirmasi hapus
        $('.close, #cancelDelete').click(function() {
            $('#confirmModal').hide();
        });

        // Konfirmasi hapus
        $('#confirmDelete').click(function() {
            var id = $('#delete_id').val();
            
            $.ajax({
                url: 'api/bookings.php?id=' + id,
                type: 'DELETE',
                success: function(response) {
                    alert(response.message);
                    $('#confirmModal').hide();
                    loadBookings();
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                }
            });
        });

        // Fungsi untuk memuat data booking
        function loadBookings() {
            $.ajax({
                url: 'api/bookings.php',
                type: 'GET',
                success: function(response) {
                    var html = '';
                    $.each(response.data, function(key, booking) {
                        var statusClass = 'status-' + booking.status;
                        html += '<tr>';
                        html += '<td>' + booking.booking_id + '</td>';
                        html += '<td>' + booking.user_id + '</td>';
                        html += '<td>' + booking.workshop_id + '</td>';
                        html += '<td>' + booking.service_id + '</td>';
                        html += '<td>' + booking.booking_date + '</td>';
                        html += '<td>' + booking.booking_time + '</td>';
                        html += '<td><span class="' + statusClass + '">' + booking.status + '</span></td>';
                        html += '<td>';
                        html += '<button class="btn-info" onclick="editBooking(' + booking.booking_id + ')">Edit</button>';
                        html += '<button class="btn-danger" onclick="showDeleteModal(' + booking.booking_id + ')">Hapus</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    $('#tabelTransaksi tbody').html(html);
                },
                error: function(xhr) {
                    alert('Gagal memuat data booking');
                }
            });
        }

        // Fungsi reset form
        function resetForm() {
            $('#formBooking')[0].reset();
            $('#booking_id').val('');
            $('#btnSubmit').val('Tambah Booking');
            $('#btnCancel').hide();
        }
    });

    // Fungsi edit booking (didefinisikan di global scope agar bisa dipanggil dari onclick)
    function editBooking(id) {
        $.ajax({
            url: 'api/bookings.php?id=' + id,
            type: 'GET',
            success: function(response) {
                var booking = response.data;
                $('#booking_id').val(booking.booking_id);
                $('#user_id').val(booking.user_id);
                $('#workshop_id').val(booking.workshop_id);
                $('#service_id').val(booking.service_id);
                $('#booking_date').val(booking.booking_date);
                $('#booking_time').val(booking.booking_time);
                $('#status').val(booking.status);
                $('#btnSubmit').val('Update Booking');
                $('#btnCancel').show();
            },
            error: function(xhr) {
                alert('Gagal memuat data booking');
            }
        });
    }

    // Fungsi show delete modal
    function showDeleteModal(id) {
        $('#delete_id').val(id);
        $('#confirmModal').show();
    }
    </script>
</body>
</html>
