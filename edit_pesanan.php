<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID pesanan tidak ditemukan.";
    exit;
}

$id = $_GET['id'];

// Ambil data pesanan
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

// Update jika form dikirim
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $waktu = $_POST['waktu'];

    // Update data pelanggan
    $updatePel = mysqli_query($conn, "UPDATE pelanggan SET nama='$nama', kelas='$kelas' WHERE id_pelanggan='{$data['id_pelanggan']}'");

    // Update waktu pesanan
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
</head>
<body>
    <h2>Edit Pesanan ID <?= $id ?></h2>
    <form method="post">
        <label>Nama Pelanggan:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required><br><br>

        <label>Kelas:</label><br>
        <input type="text" name="kelas" value="<?= htmlspecialchars($data['kelas']) ?>" required><br><br>

        <label>Waktu Pesanan:</label><br>
        <input type="datetime-local" name="waktu" value="<?= date('Y-m-d\TH:i', strtotime($data['waktu_pesanan'])) ?>" required><br><br>

        <button type="submit" name="simpan">Simpan Perubahan</button>
        <a href="riwayat_pesanan.php">Kembali</a>
    </form>
</body>
</html>
