<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID pesanan tidak ditemukan.";
    exit;
}

$id = $_GET['id'];

// Ambil data pesanan + pelanggan
$query = "
    SELECT p.*, pel.id_pelanggan, pel.nama, pel.kelas 
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

// Ambil data detail pesanan (produk, varian, jumlah)
$detail = [];
$detailQuery = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE id_pesanan = '$id'");
while ($row = mysqli_fetch_assoc($detailQuery)) {
    $detail[] = $row;
}

// Ambil data produk dan varian untuk pilihan di form
$produkResult = mysqli_query($conn, "SELECT * FROM produk");
$produkList = [];
while ($row = mysqli_fetch_assoc($produkResult)) {
    $produkList[] = $row;
}

$varianResult = mysqli_query($conn, "SELECT * FROM varian");
$varianList = [];
while ($row = mysqli_fetch_assoc($varianResult)) {
    // Kelompokkan varian berdasarkan id_produk
    $varianList[$row['id_produk']][] = $row;
}

// Proses simpan perubahan
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $waktu = $_POST['waktu'];

    $produkInput = $_POST['produk'];   // array produk[]
    $varianInput = $_POST['varian'];   // array varian[]
    $jumlahInput = $_POST['jumlah'];   // array jumlah[]

    // Update pelanggan
    $updatePel = mysqli_query($conn, "UPDATE pelanggan SET nama='$nama', kelas='$kelas' WHERE id_pelanggan='{$data['id_pelanggan']}'");

    // Update pesanan
    $updatePesanan = mysqli_query($conn, "UPDATE pesanan SET waktu_pesanan='$waktu' WHERE id_pesanan='$id'");

    // Jika update pelanggan & pesanan berhasil, update detail pesanan
    if ($updatePel && $updatePesanan) {
        // Hapus detail pesanan lama
        mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_pesanan='$id'");

        // Simpan detail pesanan baru
        foreach ($produkInput as $index => $id_produk) {
            $id_varian = $varianInput[$index];
            $jumlah = (int) $jumlahInput[$index];
        
            if ($id_produk && $id_varian && $jumlah > 0) {
                // Ambil harga produk
                $resultHarga = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk = '$id_produk'");
                $dataHarga = mysqli_fetch_assoc($resultHarga);
                $harga = $dataHarga ? $dataHarga['harga'] : 0;
        
                $total_harga = $harga * $jumlah;
        
                // Insert dengan total_harga
                mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, id_varian, jumlah, total_harga) VALUES ('$id', '$id_produk', '$id_varian', '$jumlah', '$total_harga')");
            }
        }        

        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='lihat_pesanan.php';</script>";
        exit;
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
    <script>
        const semuaVarian = <?= json_encode($varianList); ?>;

        function updateVarian(selectProduk, selectVarian, selectedVarianId = null) {
            const idProduk = selectProduk.value;
            selectVarian.innerHTML = '<option value="">-- Pilih Varian --</option>';
            if (semuaVarian[idProduk]) {
                semuaVarian[idProduk].forEach(v => {
                    const opt = document.createElement("option");
                    opt.value = v.id_varian;
                    opt.textContent = v.nama_varian;
                    if (v.id_varian == selectedVarianId) {
                        opt.selected = true;
                    }
                    selectVarian.appendChild(opt);
                });
            }
        }

        function tambahPesanan(produkId = '', varianId = '', jumlah = 1) {
            const container = document.getElementById("pesanan-container");
            const template = document.getElementById("pesanan-template").content.cloneNode(true);

            const produk = template.querySelector(".produk");
            const varian = template.querySelector(".varian");
            const jumlahInput = template.querySelector(".jumlah");

            const index = document.querySelectorAll(".pesanan-item").length;
            produk.name = `produk[${index}]`;
            varian.name = `varian[${index}]`;
            jumlahInput.name = `jumlah[${index}]`;

            produk.value = produkId;
            jumlahInput.value = jumlah;

            produk.addEventListener("change", () => updateVarian(produk, varian));
            updateVarian(produk, varian, varianId);

            container.appendChild(template);
        }

        window.addEventListener("DOMContentLoaded", () => {
            // Data detail pesanan lama dari PHP ke JS
            const dataLama = <?= json_encode($detail); ?>;
            if (dataLama.length > 0) {
                dataLama.forEach(p => {
                    tambahPesanan(p.id_produk, p.id_varian, p.jumlah);
                });
            } else {
                tambahPesanan(); // Jika belum ada detail, tampilkan 1 baris kosong
            }
        });
    </script>
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

            <h3>Daftar Menu</h3>
            <div id="pesanan-container"></div>

            <template id="pesanan-template">
                <div class="pesanan-item" style="margin-bottom: 10px;">
                    <select class="produk" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($produkList as $p) : ?>
                            <option value="<?= $p['id_produk'] ?>"><?= htmlspecialchars($p['nama_produk']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="varian" required>
                        <option value="">-- Pilih Varian --</option>
                    </select>
                    <input type="number" class="jumlah" placeholder="Jumlah" min="1" required>
                </div>
            </template>

            <button type="button" onclick="tambahPesanan()">➕ Tambah Menu</button><br><br>

            <button type="submit" name="simpan">Simpan Perubahan</button>
            <button type="button" onclick="window.location.href='lihat_pesanan.php'">Kembali</button>
        </form>
    </div>
</body>
</html>