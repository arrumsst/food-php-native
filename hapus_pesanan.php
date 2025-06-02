<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus detail pesanan dulu (agar tidak ada foreign key constraint error)
    $deleteDetail = mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_pesanan = '$id'");

    // Hapus pesanan utama
    $deletePesanan = mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan = '$id'");

    if ($deleteDetail && $deletePesanan) {
        echo "<script>alert('Pesanan berhasil dihapus!'); window.location.href='lihat_pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus pesanan.'); window.location.href='lihat_pesanan.php';</script>";
    }
} else {
    echo "<script>alert('ID pesanan tidak ditemukan.'); window.location.href='lihat_pesanan.php';</script>";
}
?>
