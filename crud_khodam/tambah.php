<?php
// Aktifkan error reporting untuk debugging (hapus atau nonaktifkan di produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

// Inisialisasi variabel
$nama_khodam = $jenis_khodam = "";
$error = "";
$success = "";

// Proses penyimpanan data setelah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Periksa apakah 'nama_khodam' dan 'jenis_khodam' ada
    if (isset($_POST['nama_khodam']) && isset($_POST['jenis_khodam'])) {
        $nama_khodam = trim($_POST['nama_khodam']);
        $jenis_khodam = trim($_POST['jenis_khodam']);

        // Validasi input
        if (empty($nama_khodam) || empty($jenis_khodam)) {
            $error = "Semua field harus diisi.";
        } else {
            // Insert data ke database dengan prepared statements untuk keamanan
            $stmt = $conn->prepare("INSERT INTO list_khodam (nama_khodam, jenis_khodam) VALUES (?, ?)");
            $stmt->bind_param("ss", $nama_khodam, $jenis_khodam);

            if ($stmt->execute()) {
                // Redirect atau tampilkan pesan sukses
                echo "<script>alert('Data berhasil ditambahkan'); window.location.href='table.php';</script>";
                exit();
            } else {
                $error = "Error menambahkan data: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        $error = "Data form tidak lengkap.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Khodam</title>
    <style>
        form {
            width: 300px;
            margin: 0 auto;
        }
        label, input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            width: auto;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: blue;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Tambah Khodam</h2>

<?php
if (!empty($error)) {
    echo "<p class='error'>$error</p>";
}
?>

<form method="POST" action="tambah.php">
    <label for="nama_khodam">Nama Khodam:</label>
    <input type="text" id="nama_khodam" name="nama_khodam" value="<?php echo htmlspecialchars($nama_khodam); ?>" required>

    <label for="jenis_khodam">Jenis Khodam:</label>
    <input type="text" id="jenis_khodam" name="jenis_khodam" value="<?php echo htmlspecialchars($jenis_khodam); ?>" required>

    <input type="submit" name="submit" value="Tambah">
</form>

<a href="table.php">Kembali ke Daftar</a>

</body>
</html>