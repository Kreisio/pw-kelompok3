<?php
include 'koneksi.php';

// Hapus data jika parameter 'delete_id' ada di URL
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Pastikan ID adalah angka untuk mencegah SQL Injection
    if (is_numeric($id)) {
        // Gunakan prepared statements untuk keamanan
        $stmt = $conn->prepare("DELETE FROM list_khodam WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil dihapus'); window.location.href='table.php';</script>";
        } else {
            echo "Error menghapus data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "ID tidak valid.";
    }
}

// Ambil data dari database
$sql = "SELECT * FROM list_khodam";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Khodam</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            margin-right: 10px;
            text-decoration: none;
            color: blue;
        }
        a:hover {
            text-decoration: underline;
        }
        .add-button {
            display: inline-block;
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .add-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>List Khodam</h2>

<!-- Tombol Tambah -->
<a href="tambah.php" class="add-button">Tambah Khodam</a>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Khodam</th>
            <th>Jenis Khodam</th>
            <th>Aksi</th> <!-- Kolom untuk tombol Edit dan Delete -->
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            // Menampilkan data untuk setiap baris
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["nama_khodam"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["jenis_khodam"]) . "</td>";
                echo "<td>
                        <a href='edit.php?id=" . urlencode($row["id"]) . "'>Edit</a>
                        <a href='table.php?delete_id=" . urlencode($row["id"]) . "' onclick=\"return confirm('Yakin ingin menghapus?');\">Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
        }
        ?>              
    </tbody>
</table>

</body>
</html>
<?php
$conn->close();
?>
