<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID pesanan tidak ditemukan.";
    exit;
}

$id = $_GET['id'];

$query = "
    SELECT p.*, pel.nama, pel.kelas 
    FROM pesanan p 
    JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan 
    WHERE p.id_pesanan = '$id'
";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $waktu = $_POST['waktu'];

    $updatePel = mysqli_query($conn, "UPDATE pelanggan SET nama='$nama', kelas='$kelas' WHERE id_pelanggan='{$data['id_pelanggan']}'");
    $updatePesanan = mysqli_query($conn, "UPDATE pesanan SET waktu_pesanan='$waktu' WHERE id_pesanan='$id'");

    if ($updatePel && $updatePesanan) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='riwayat_pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pesanan</title>
    <link rel="stylesheet" href="sty3.css">
</head>
<body>
    <div class="main-container">
        <h2>Edit Pesanan ID <?= $id ?></h2>
        <form method="post">
            <h3>Data Pelanggan</h3>
            <label>Nama Pelanggan:
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </label>

            <label>Kelas:
                <input type="text" name="kelas" value="<?= htmlspecialchars($data['kelas']) ?>" required>
            </label>

            <h3>Waktu Pesanan</h3>
            <label>Waktu:
                <input type="datetime-local" name="waktu" value="<?= date('Y-m-d\TH:i', strtotime($data['waktu_pesanan'])) ?>" required>
            </label>

            <br><br>
            <button type="submit" name="simpan">Simpan Perubahan</button>
            <button type="button" onclick="window.location.href='riwayat_pesanan.php'">Kembali</button>
        </form>
    </div>
</body>
</html>