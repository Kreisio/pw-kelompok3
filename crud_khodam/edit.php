<?php
// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

// Inisialisasi variabel
$id = $nama_khodam = $jenis_khodam = "";
$error = "";

// Cek apakah ID disediakan melalui GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Pastikan ID adalah angka untuk mencegah SQL Injection
    if (is_numeric($id)) {
        // Gunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("SELECT * FROM list_khodam WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Mengambil data untuk diisi ke form
            $row = $result->fetch_assoc();
            $nama_khodam = $row['nama_khodam'];
            $jenis_khodam = $row['jenis_khodam'];
        } else {
            echo "Data tidak ditemukan.";
            exit();
        }
        
        $stmt->close();
    } else {
        echo "ID tidak valid.";
        exit();
    }
}

// Proses pembaruan data setelah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Debugging: Tampilkan isi $_POST
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    
    // Periksa apakah 'id', 'nama_khodam', dan 'jenis_khodam' ada
    if (isset($_POST['id']) && isset($_POST['nama_khodam']) && isset($_POST['jenis_khodam'])) {
        $id = $_POST['id'];
        $nama_khodam = $_POST['nama_khodam'];
        $jenis_khodam = $_POST['jenis_khodam'];
        
        // Validasi input
        if (empty($nama_khodam) || empty($jenis_khodam)) {
            $error = "Semua field harus diisi.";
        } else {
            // Update data di database dengan prepared statements untuk keamanan
            $stmt = $conn->prepare("UPDATE list_khodam SET nama_khodam = ?, jenis_khodam = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nama_khodam, $jenis_khodam, $id);
            
            if ($stmt->execute()) {
                echo "<script>alert('Data berhasil diupdate'); window.location.href='table.php';</script>";
                exit();
            } else {
                echo "Error mengupdate data: " . $stmt->error;
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
    <title>Edit Khodam</title>
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

<h2 style="text-align:center;">Edit Khodam</h2>

<?php
if (!empty($error)) {
    echo "<p class='error'>$error</p>";
}
?>

<form method="POST" action="edit.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    
    <label for="nama_khodam">Nama Khodam:</label>
    <input type="text" id="nama_khodam" name="nama_khodam" value="<?php echo htmlspecialchars($nama_khodam); ?>" required>
    
    <label for="jenis_khodam">Jenis Khodam:</label>
    <input type="text" id="jenis_khodam" name="jenis_khodam" value="<?php echo htmlspecialchars($jenis_khodam); ?>" required>
    
    <input type="submit" name="update" value="Update">
</form>

<a href="table.php">Kembali ke Daftar</a>

</body>
</html>
