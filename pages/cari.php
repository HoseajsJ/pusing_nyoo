<?php
session_start();
require_once '/includes/db.php'; 

// Redirect jika pengguna belum login
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

$where_clauses = [];
$params = [];
$param_types = ''; 

// Handle search keyword
if (!empty($_GET['keyword'])) {
    $keyword = '%' . $_GET['keyword'] . '%';
    $where_clauses[] = "(nama LIKE ? OR alamat LIKE ?)";
    $params[] = $keyword;
    $params[] = $keyword;
    $param_types .= 'ss'; 
}

// Handle category filter
if (!empty($_GET['kategori'])) {
    $kategori = $_GET['kategori'];
    $where_clauses[] = "kategori = ?";
    $params[] = $kategori;
    $param_types .= 's'; // satu string
}

// Gabungkan semua klausa WHERE
$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

// Siapkan dan jalankan query
$query = "SELECT * FROM bengkel" . $where_sql;
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

// Bind parameter jika ada
if (!empty($params)) {
    // Memastikan array $params dilewatkan sebagai referensi untuk call_user_func_array
    $bind_args = array_merge([$param_types], $params);
    call_user_func_array([$stmt, 'bind_param'], ref_values($bind_args));
}

function ref_values($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) // PHP 5.3+
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cari Bengkel</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/cari.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <button class="menu-toggle" onclick="toggleMenu()">&#8942;</button>

  <div class="slide-menu" id="slideMenu">
    <a href="cari.php">🏠 Home</a>
    <a href="transaksi.php">📋 Transaksi</a>
    <a href="../logout.php">🚪 Logout</a>
  </div>
  <div class="overlay" onclick="toggleMenu()" id="overlay"></div>

  <header class="header">
    <h1>🔧 Cari Bengkel</h1>
    <p>Temukan bengkel terpercaya untuk kendaraanmu</p>
  </header>

  <div class="search-wrapper">
    <form method="GET">
      <input type="text" name="keyword" placeholder="Cari layanan..." class="search-input" value="<?= htmlspecialchars($_GET['keyword'] ?? ''); ?>">
      <select name="kategori" class="search-select">
        <option value="">Semua Kategori</option>
        <option value="Mobil" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'Mobil') ? 'selected' : ''; ?>>Mobil</option>
        <option value="Motor" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'Motor') ? 'selected' : ''; ?>>Motor</option>
        <option value="Mobil & Motor" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'Mobil & Motor') ? 'selected' : ''; ?>>Mobil & Motor</option>
      </select>
      <button type="submit" class="search-btn">Cari</button>
    </form>
  </div>

  <div class="grid-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($b = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="../assets/img/<?= htmlspecialchars($b['foto'] ?? 'default.jpg'); ?>" alt="<?= htmlspecialchars($b['nama']); ?>" class="bengkel-img">
            
            <button class="btn-favorite" data-id="<?= htmlspecialchars($b['id']); ?>">
                <i class="far fa-heart"></i>
            </button>
            <h3><?= htmlspecialchars($b['nama']); ?></h3>
            <p>📍 <?= htmlspecialchars($b['alamat']); ?></p>
            <a href="booking.php?id=<?= htmlspecialchars($b['id']); ?>" class="btn-book">💳 Booking</a>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; width: 100%; color: #555;">Tidak ada bengkel ditemukan dengan kriteria tersebut.</p>
    <?php endif; ?>
  </div>

  <script src="../assets/js/cari.js"></script>
</body>
</html>
<?php
session_start();
require_once '../includes/db.php'; 

// Redirect jika pengguna belum login
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$where_clauses = [];
$params = [];
$param_types = ''; 

// Handle search keyword
if (!empty($_GET['keyword'])) {
    $keyword = '%' . $_GET['keyword'] . '%';
    $where_clauses[] = "(nama LIKE ? OR alamat LIKE ?)";
    $params[] = $keyword;
    $params[] = $keyword;
    $param_types .= 'ss'; 
}

// Handle category filter
if (!empty($_GET['kategori'])) {
    $kategori = $_GET['kategori'];
    $where_clauses[] = "kategori = ?";
    $params[] = $kategori;
    $param_types .= 's'; // satu string
}

// Gabungkan semua klausa WHERE
$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

// Siapkan dan jalankan query
$query = "SELECT * FROM bengkel" . $where_sql;
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

// Bind parameter jika ada
if (!empty($params)) {
    // Memastikan array $params dilewatkan sebagai referensi untuk call_user_func_array
    $bind_args = array_merge([$param_types], $params);
    call_user_func_array([$stmt, 'bind_param'], ref_values($bind_args));
}

function ref_values($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) // PHP 5.3+
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cari Bengkel</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/cari.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <button class="menu-toggle" onclick="toggleMenu()">&#8942;</button>

  <div class="slide-menu" id="slideMenu">
    <a href="cari.php">🏠 Home</a>
    <a href="transaksi.php">📋 Transaksi</a>
    <a href="../logout.php">🚪 Logout</a>
  </div>
  <div class="overlay" onclick="toggleMenu()" id="overlay"></div>

  <header class="header">
    <h1>🔧 Cari Bengkel</h1>
    <p>Temukan bengkel terpercaya untuk kendaraanmu</p>
  </header>

  <div class="search-wrapper">
    <form method="GET">
      <input type="text" name="keyword" placeholder="Cari layanan..." class="search-input" value="<?= htmlspecialchars($_GET['keyword'] ?? ''); ?>">
      <select name="kategori" class="search-select">
        <option value="">Semua Kategori</option>
        <option value="Mobil" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'Mobil') ? 'selected' : ''; ?>>Mobil</option>
        <option value="Motor" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'Motor') ? 'selected' : ''; ?>>Motor</option>
        <option value="Mobil & Motor" <?= (isset($_GET['kategori']) && $_GET['kategori'] == 'Mobil & Motor') ? 'selected' : ''; ?>>Mobil & Motor</option>
      </select>
      <button type="submit" class="search-btn">Cari</button>
    </form>
  </div>

  <div class="grid-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($b = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="../assets/img/<?= htmlspecialchars($b['foto'] ?? 'default.jpg'); ?>" alt="<?= htmlspecialchars($b['nama']); ?>" class="bengkel-img">
            
            <button class="btn-favorite" data-id="<?= htmlspecialchars($b['id']); ?>">
                <i class="far fa-heart"></i>
            </button>
            <h3><?= htmlspecialchars($b['nama']); ?></h3>
            <p>📍 <?= htmlspecialchars($b['alamat']); ?></p>
            <a href="booking.php?id=<?= htmlspecialchars($b['id']); ?>" class="btn-book">💳 Booking</a>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; width: 100%; color: #555;">Tidak ada bengkel ditemukan dengan kriteria tersebut.</p>
    <?php endif; ?>
  </div>

  <script src="../assets/js/cari.js"></script>
</body>
</html>
