<?php
include 'koneksi.php';
$query = "
    SELECT
        p.id_pesanan,
        pel.nama,
        pel.kelas,
        dp.id_produk,
        pr.nama_produk,
        dp.id_varian,
        v.nama_varian,
        dp.jumlah,
        dp.total_harga,
        p.waktu_pesanan
    FROM pesanan p
    JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
    JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
    JOIN produk pr ON dp.id_produk = pr.id_produk
    JOIN varian v ON dp.id_varian = v.id_varian
    ORDER BY p.id_pesanan DESC
";
$result = mysqli_query($conn, $query);
$pesananData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id_pesanan'];
    if (!isset($pesananData[$id])) {
        $pesananData[$id] = [
            'pelanggan' => $row['nama'],
            'kelas' => $row['kelas'],
            'items' => [],
            'waktu_pesanan' => $row['waktu_pesanan'],
        ];
    }
    $pesananData[$id]['items'][] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pesanan</title>
    <link rel="stylesheet" href="sty2.css">
    <style>
        .action-buttons .btn {
            margin: 5px;
        }
        .pesanan-actions {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Riwayat Semua Pesanan</h2>
    
    <div class="action-buttons">
        <a href="tambah_pesanan.php" class="btn">Tambah Pesanan Baru</a>
        <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>
    
    <?php if (empty($pesananData)): ?>
        <div style="text-align: center; margin: 50px 0;">
            <p>Belum ada pesanan yang dibuat.</p>
        </div>
    <?php else: ?>
        <?php foreach ($pesananData as $id => $pesanan): ?>
            <div class="pesanan">
                <h3>
                    Pesanan ID: <?= $id ?> | Pelanggan: <?= htmlspecialchars($pesanan['pelanggan']) ?> (<?= htmlspecialchars($pesanan['kelas']) ?>) <br>
                    Waktu Pesanan: <?= $pesanan['waktu_pesanan'] ?>
                </h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $totalPesanan = 0;
                        foreach ($pesanan['items'] as $item):
                            $totalPesanan += $item['total_harga'];
                            $hargaSatuan = $item['total_harga'] / $item['jumlah'];
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                            <td><?= htmlspecialchars($item['nama_varian']) ?></td>
                            <td><?= $item['jumlah'] ?></td>
                            <td>Rp <?= number_format($hargaSatuan, 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($item['total_harga'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5"><strong>Total Pesanan</strong></td>
                            <td><strong>Rp <?= number_format($totalPesanan, 0, ',', '.') ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <div class="pesanan-actions">
                    <a href="edit_pesanan.php?id=<?= $id ?>" class="btn">Edit</a>
                    <a href="hapus_pesanan.php?id=<?= $id ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>