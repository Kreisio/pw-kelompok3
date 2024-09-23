<?php 
include 'koneksi.php';

function getRandomKhodam($conn) {
    $sql = "SELECT nama_khodam, jenis_khodam FROM list_khodam";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $khodamList = [];

        while ($row = $result->fetch_assoc()) {
            $khodamList[] = $row;
        }
        $randomIndex = mt_rand(0, count($khodamList) - 1);

        return $khodamList[$randomIndex];
    } else {
        return null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khodam</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: blue;
            font-family: 'Arial', sans-serif;
            color: #fff;
        }
        h1 {
            font-size: 4em;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin: 0;
        }
        p {
            font-size: 1.5em;
            margin: 20px 0;
        }
        .search-container {
            margin-top: 20px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            width: 300px;
        }
        button {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #666;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div>
    <h1>Selamat Datang</h1>
    <p>Cari tau khodam anda disini!!!</p>
    <div class="search-container">
        <form method="post" action="">
            <input type="text" name="khodam" placeholder="ex: bambang ...">
            <button type="submit">Cari</button>
        </form>
    </div>
    <?php
    // Memproses data jika formulir disubmit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $inputName = trim($_POST['khodam']);
        if (empty($inputName)) {
            echo '<p class="error">Nama tidak boleh kosong.</p>';
        } else {
            $khodam = getRandomKhodam($conn);
            if ($khodam) {
                echo "<h2>Hasil:</h2>";
                echo "<p><strong>Nama:</strong> $inputName</p>";
                echo "<p><strong>Kodam:</strong> {$khodam['nama_khodam']}</p>";
                echo "<p><strong>Jenis Kodam:</strong> {$khodam['jenis_khodam']}</p>";
            } else {
                echo '<p class="error">Tidak ada data kodam ditemukan.</p>';
            }
        }
    }
    $conn->close();
    ?>
</div>
</body>
</html>
