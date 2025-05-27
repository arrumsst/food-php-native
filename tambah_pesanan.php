<?php
include 'koneksi.php';
$produk = mysqli_query($conn, "SELECT * FROM produk");
$varian = [];
$res = mysqli_query($conn, "SELECT * FROM varian");
while ($row = mysqli_fetch_assoc($res)) {
    $varian[$row['id_produk']][] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Pesanan</title>
    <link rel="stylesheet" href="sty1.css">
    <script>
        const semuaVarian = <?= json_encode($varian); ?>;
        function updateVarian(selectProduk, selectVarian) {
            const idProduk = selectProduk.value;
            selectVarian.innerHTML = '<option value="">-- Pilih Varian --</option>';
            if (semuaVarian[idProduk]) {
                semuaVarian[idProduk].forEach(v => {
                    const opt = document.createElement("option");
                    opt.value = v.id_varian;
                    opt.textContent = v.nama_varian;
                    selectVarian.appendChild(opt);
                });
            }
        }
        function tambahPesanan() {
            const container = document.getElementById("pesanan-container");
            const template = document.getElementById("pesanan-template").content.cloneNode(true);
            
            const produk = template.querySelector(".produk");
            const varian = template.querySelector(".varian");
            
            produk.addEventListener("change", () => updateVarian(produk, varian));
            
            // Pastikan nama element unik
            const pesananItems = document.querySelectorAll(".pesanan-item").length;
            produk.name = `produk[${pesananItems}]`;
            varian.name = `varian[${pesananItems}]`;
            template.querySelector(".jumlah").name = `jumlah[${pesananItems}]`;
            
            container.appendChild(template);
        }
        
        window.addEventListener("DOMContentLoaded", () => {
            tambahPesanan(); // Tambahkan satu form awal
        });
    </script>
</head>
<body>
    <h2>Form Pesanan Baru</h2>
    <form action="simpan_pesanan.php" method="POST">
        <h3>Data Pelanggan</h3>
        <label>Nama Pelanggan: <input type="text" name="nama_pelanggan" required></label><br><br>
        <label>Kelas Pelanggan: <input type="text" name="kelas_pelanggan" required></label><br><br>
        <h3>Pesanan</h3>
        <div id="pesanan-container"></div>
        
        <template id="pesanan-template">
            <div class="pesanan-item" style="margin-bottom: 10px;">
                <select class="produk" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php mysqli_data_seek($produk, 0); ?>
                    <?php while ($p = mysqli_fetch_assoc($produk)) : ?>
                        <option value="<?= $p['id_produk'] ?>"><?= htmlspecialchars($p['nama_produk']) ?></option>
                    <?php endwhile; ?>
                </select>
                <select class="varian" required>
                    <option value="">-- Pilih Varian --</option>
                </select>
                <input type="number" class="jumlah" placeholder="Jumlah" min="1" required>
            </div>
        </template>
        
        <button type="button" onclick="tambahPesanan()">➕ Tambah Menu</button><br><br>
        <button type="submit">Simpan Pesanan</button>
        <button type="button" onclick="window.location.href='index.php'">Cancel</button>
    </form>
</body>
</html>