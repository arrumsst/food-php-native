<?php
include 'koneksi.php';

// Ambil semua produk & varian
$query = "
    SELECT p.nama_produk, v.nama_varian, p.harga
    FROM produk p
    JOIN varian v ON p.id_produk = v.id_produk
    ORDER BY p.nama_produk, v.nama_varian
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Menu - Cireng Cimay</title>
    <link rel="stylesheet" href="sty.css">
 
        <header>
        <nav>
          <div class="logo">Melt Me Moy</div>
          <ul class="nav-links">
            <li><a href="#menu">Menu</a></li>
            <li><a href="#daftarvarian">Daftar Varian & Harga</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </nav>
      </header>
      <section class="hero">
        <div class="hero-content">
            <img src="./image/logomeltme.jpg" alt="Logo" />
            <h1>Hello!!<br>Melt Me Moy Loverss...</h1>
            <p>Selamat datang di website kami!<br>Kita menyediakan berbagai menu cireng dan cimay yang lezat dan menggugah selera.<br>Silakan lihat menu kami di bawah ini.<p>
        </div>
      </section>
</head>
<body>


    <h2>Daftar Menu Cireng & Cimay</h2>

<div class="gambar-menu" id="menu">
    <div class="menu-item">
        <img src="./image/cireng.jpg" alt="Cireng" />
        <p>Cireng Renyah Isi Ayam Suwir Pedas</p>
    </div>
    <div class="menu-item">
        <img src="./image/cirengkeju.jpeg" alt="CirengKeju" />
        <p>Cireng Renyah Isi Keju</p>
    </div>
    <div class="menu-item">
        <img src="./image/cimay.jpg" alt="Cimay" />
        <p>Cimay (Aci Siomay)</p>
    </div>
    <div class="menu-item">
        <img src="./image/chilioil.jpg" alt="Chili Oil" />
        <p>Chili Oil Pedas Gurih<P>
        <p>Cocok untuk Cireng & Cimay</p>
    </div>
</div>
<br>

<div class="gambar-menu" id="daftarvarian">
    <h2>Daftar Varian Menu</h2>
    <table>
        <tr>
            <th>Menu</th>
            <th>Varian</th>
            <th>Harga</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['nama_produk'] ?></td>
            <td><?= $row['nama_varian'] ?></td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
    <br>

    <div class="btn-container">
        <a href="tambah_pesanan.php" class="btn">➕ Input Pesanan</a>
        <a href="lihat_pesanan.php" class="btn">Riwayat Pemesanan</a>
    </div>

<!-- About Us Section -->
<div class="about" id="about">
    <h3>Tentang Kami</h3>
    <p>
        Di balik lezatnya Cireng Chili Oil dan Cimay yang kamu nikmati, terdapat kerja keras dan dedikasi dari empat pelajar SMKN 4 Padalarang yang penuh semangat.
        Kami mungkin bukan chef profesional atau pebisnis berpengalaman, namun kami adalah generasi muda yang berani mencoba, belajar dari kegagalan, dan terus berkembang.
        Dengan semangat kolaborasi, kami menciptakan produk yang tidak hanya enak, tetapi juga memiliki cerita dan proses di baliknya.
        Yuk, kenalan lebih dekat dengan tim kami!
    </p>
    
    <div class="team-members">
        <div class="team-member">
            <img src="./image/arum.jpg" alt="arum" />
            <p>Arum Sekar Sary Tanjung</p>
            <h4>Backend</h4>
        </div>
        <div class="team-member">
            <img src="./image/dea.jpg" alt="dea" />
            <p>Dea Wahyu Putri</p>
            <h4>Desainer</h4>
        </div>
        <div class="team-member">
            <img src="./image/niza.jpg" alt="niza" />
            <p>Niza Arsy Yuliarahma</p>
            <h4>Frontend</h4>
        </div>
        <div class="team-member">
            <img src="./image/shifa.jpg" alt="shifa" />
            <p>Shifa Nayla Arthalita</p>
            <h4>UI/UX</h4>
        </div>
    </div>
</div>

<!-- Contact Section -->
<section class="contact" id="contact">
  <div class="container">
    <h3>Hubungi Kami</h3>
    <p>Jika Anda memiliki pertanyaan atau ingin melakukan pemesanan, silakan hubungi kami di:</p>
    <p>
      <strong>WhatsApp:</strong><br>
      <a href="https://wa.me/6283823595353" target="_blank" rel="noopener">
        Arum
      </a>
      <a href="https://wa.me/6285861812817" target="_blank" rel="noopener">
        Dea
      </a>
      <a href="https://wa.me/6285864182902" target="_blank" rel="noopener">
        Niza
      </a>
      <a href="https://wa.me/6281395487049" target="_blank" rel="noopener">
        Shifa
      </a>
    </p>
    <p>
      <strong>Email:</strong>
      <a href="mailto:kitaempat44@gmail.com">
        kitaempat44@gmail.com
      </a>
    </p>
  </div>
</section>


    <div class="footer" id="footer">
        <p>&copy; 2025 Melt Me Moy. All rights reserved.</p>

</body>
</html>
