<?php
include 'koneksi.php';
$nama_pelanggan = mysqli_real_escape_string($conn, $_POST['nama_pelanggan']);
$kelas_pelanggan = mysqli_real_escape_string($conn, $_POST['kelas_pelanggan']);
$produk = $_POST['produk'];
$varian = $_POST['varian'];
$jumlah = $_POST['jumlah'];

// Debugging
if (empty($produk) || empty($varian) || empty($jumlah)) {
    echo "<pre>Debug POST data: ";
    print_r($_POST);
    echo "</pre>";
    die("Data pesanan tidak lengkap. Silakan kembali dan pastikan semua field diisi.");
}

// Insert data pelanggan (cek dulu apakah sudah ada di database atau belum)
$result = mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan WHERE nama='$nama_pelanggan' AND kelas='$kelas_pelanggan'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $id_pelanggan = $row['id_pelanggan'];
} else {
    mysqli_query($conn, "INSERT INTO pelanggan (nama, kelas) VALUES ('$nama_pelanggan', '$kelas_pelanggan')");
    $id_pelanggan = mysqli_insert_id($conn);
}

// Insert pesanan baru dengan waktu pesanan
$waktu_sekarang = date('Y-m-d H:i:s');
mysqli_query($conn, "INSERT INTO pesanan (id_pelanggan, waktu_pesanan) VALUES ($id_pelanggan, '$waktu_sekarang')");
$id_pesanan = mysqli_insert_id($conn);

for ($i = 0; $i < count($varian); $i++) {
    $id_varian = (int) $varian[$i];
    $qty = (int) $jumlah[$i];
    
    // Ambil id_produk & harga produk
    $query = "
        SELECT v.id_produk, p.harga
        FROM varian v
        JOIN produk p ON v.id_produk = p.id_produk
        WHERE v.id_varian = $id_varian
    ";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    $id_produk = $row['id_produk'];
    $harga = $row['harga'];
    $total = $harga * $qty;
    
    // Insert detail pesanan (detail_pesanan)
    $sqlDetail = "
        INSERT INTO detail_pesanan (id_pesanan, id_produk, id_varian, jumlah, total_harga)
        VALUES ($id_pesanan, $id_produk, $id_varian, $qty, $total)
    ";
    mysqli_query($conn, $sqlDetail);
}

header("Location: lihat_pesanan.php");
exit;