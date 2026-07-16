<?php
include "includes/koneksi.php";
include "includes/auth.php";

$totalBuku     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
$totalStok     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM buku"))['total'];
$totalKategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT kategori) as total FROM buku"))['total'];

$bukuTerbaru = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC LIMIT 5");
$kategoriList = mysqli_query($conn, "SELECT kategori, COUNT(*) as jumlah FROM buku GROUP BY kategori ORDER BY jumlah DESC LIMIT 6");

$paletteRak = ['#8a5a44', '#3f6653', '#a3763f', '#4a5d75', '#7a4b5a', '#5c6b3f'];

$judulHalaman = "Beranda";
include "includes/header.php";
?>

<style>
    .beranda-wrap { font-family: 'Inter', sans-serif; }

    .hero-baca {
        background: #fbf7ef;
        border: 1px solid #eae1cf;
        border-radius: 16px;
        padding: 3rem 2.75rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -12px rgba(60, 45, 20, 0.15);
    }
    .hero-baca::before {
        content: "";
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(44,74,59,0.07), transparent 70%);
    }
    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.76rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #8a5a2e;
        background: #f3e6cf;
        padding: 0.35rem 0.8rem;
        border-radius: 30px;
        margin-bottom: 1.1rem;
        font-weight: 600;
    }
    .hero-tag i { font-size: 0.7rem; }
    .hero-baca h1 {
        color: #2c2620;
        font-size: 2.55rem;
        font-weight: 600;
        line-height: 1.18;
        margin-bottom: 0.9rem;
        position: relative;
        z-index: 1;
    }
    .hero-baca h1 .garis-aksen {
        position: relative;
        white-space: nowrap;
    }
    .hero-baca h1 .garis-aksen::after {
        content: "";
        position: absolute;
        left: -2px; right: -2px; bottom: 2px;
        height: 10px;
        background: #d9c087;
        opacity: 0.55;
        z-index: -1;
        border-radius: 2px;
    }
    .hero-baca p.lead {
        color: #5c5348;
        max-width: 480px;
        font-size: 1.03rem;
        position: relative;
        z-index: 1;
    }
    .btn-baca-utama {
        background: #2c4a3b;
        border: none;
        color: #fff;
        padding: 0.65rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
        box-shadow: 0 4px 12px -4px rgba(44,74,59,0.5);
    }
    .btn-baca-utama:hover {
        background: #21382d;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px -6px rgba(44,74,59,0.55);
    }
    .btn-baca-outline {
        border: 1.5px solid #2c2620;
        color: #2c2620;
        padding: 0.6rem 1.4rem;
        border-radius: 8px;
        font-weight: 500;
        background: transparent;
        transition: all 0.15s ease;
        display: inline-block;
    }
    .btn-baca-outline:hover { background: #2c2620; color: #fff; transform: translateY(-2px); }

    .ilustrasi-rak { position: relative; max-width: 240px; margin: 0 auto; }
    .tumpukan-buku { position: relative; width: 100%; }
    .balok-buku {
        height: 27px;
        border-radius: 4px 4px 3px 3px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        margin-bottom: 7px;
        position: relative;
        transition: transform 0.2s ease;
    }
    .balok-buku:hover { transform: translateX(4px); }
    .balok-buku::after {
        content: "";
        position: absolute; left: 10px; top: 4px; bottom: 4px; width: 2px;
        background: rgba(255,255,255,0.35);
    }
    .buku-berdiri {
        position: absolute;
        bottom: 0; right: 4px;
        width: 36px; height: 132px;
        border-radius: 4px 4px 2px 2px;
        background: #b8763f;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        transform: rotate(-5deg);
    }
    .buku-berdiri::before {
        content: "";
        position: absolute; left: 8px; top: 6px; bottom: 6px; width: 2px;
        background: rgba(255,255,255,0.35);
    }
    .lencana-melayang {
        position: absolute;
        top: -14px; left: -18px;
        background: #fff;
        border: 1px solid #ece6da;
        border-radius: 10px;
        padding: 0.5rem 0.8rem;
        box-shadow: 0 8px 20px -8px rgba(0,0,0,0.25);
        font-size: 0.78rem;
        color: #2c4a3b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .lencana-melayang i { color: #c7ab7a; }

    .rak-stat {
        background: #fff;
        border: 1px solid #ece6da;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        display: flex;
        justify-content: space-around;
        text-align: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .rak-stat .item-stat { min-width: 130px; }
    .rak-stat .icon-stat {
        width: 34px; height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .rak-stat .angka {
        font-family: 'Fraunces', serif;
        font-size: 2.15rem;
        font-weight: 600;
        color: #2c4a3b;
        display: block;
        line-height: 1;
    }
    .rak-stat .label {
        color: #8a8072;
        font-size: 0.85rem;
        margin-top: 0.35rem;
    }
    .rak-stat .pemisah {
        border-left: 1px solid #ece6da;
        margin: 0 0.5rem;
    }

    .chip-kategori {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #fff;
        border: 1px solid #ece6da;
        color: #4a4238;
        font-size: 0.83rem;
        font-weight: 500;
        padding: 0.4rem 0.85rem;
        border-radius: 30px;
        transition: all 0.15s ease;
    }
    .chip-kategori:hover {
        border-color: #2c4a3b;
        background: #f2f6f0;
        transform: translateY(-1px);
    }
    .chip-kategori .titik { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
    .chip-kategori .jumlah { color: #a39a8b; }

    .panel-judul { font-size: 1.18rem; color: #2c2620; margin-bottom: 0.2rem; }
    .panel-sub { color: #a39a8b; font-size: 0.88rem; margin-bottom: 1.4rem; }
    .baris-rak {
        display: flex;
        align-items: center;
        padding: 0.75rem 0.6rem;
        border-bottom: 1px dashed #e8e1d2;
        border-radius: 8px;
        transition: background 0.15s ease;
    }
    .baris-rak:hover { background: #faf7f0; }
    .baris-rak:last-child { border-bottom: none; }
    .punggung-buku {
        width: 10px;
        height: 42px;
        border-radius: 3px;
        margin-right: 1rem;
        flex-shrink: 0;
        box-shadow: 2px 2px 4px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .punggung-buku img { width: 100%; height: 100%; object-fit: cover; }
    .baris-rak .judul-buku { font-weight: 600; color: #2c2620; margin-bottom: 0.1rem; }
    .baris-rak .meta-buku { font-size: 0.82rem; color: #a39a8b; }
    .baris-rak .badge-stok {
        margin-left: auto;
        font-size: 0.78rem;
        color: #6b7d5a;
        background: #eef2e6;
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        white-space: nowrap;
    }

    .daftar-fitur { list-style: none; padding: 0; margin: 0; }
    .daftar-fitur li {
        display: flex;
        gap: 1rem;
        padding: 0.95rem 0.4rem;
        border-bottom: 1px solid #f0ebe0;
        align-items: flex-start;
        border-radius: 8px;
        transition: background 0.15s ease;
    }
    .daftar-fitur li:hover { background: #faf7f0; }
    .daftar-fitur li:last-child { border-bottom: none; }
    .nomor-fitur {
        font-family: 'Fraunces', serif;
        font-size: 1.15rem;
        color: #c7ab7a;
        min-width: 32px;
    }
    .daftar-fitur .judul-fitur { font-weight: 600; color: #2c2620; margin-bottom: 0.15rem; }
    .daftar-fitur .ket-fitur { font-size: 0.87rem; color: #8a8072; }

    .catatan-tentang {
        background: #fbf7ef;
        border-left: 3px solid #2c4a3b;
        border-radius: 4px;
        padding: 1.6rem 1.8rem;
        color: #4a4238;
        font-size: 0.95rem;
        line-height: 1.75;
        position: relative;
    }
    .catatan-tentang .tanda-kutip {
        font-family: 'Fraunces', serif;
        font-size: 3.2rem;
        color: #d9c087;
        position: absolute;
        top: -6px; left: 1.2rem;
        line-height: 1;
        opacity: 0.7;
    }

    .panel-putih {
        background: #fff;
        border: 1px solid #ece6da;
        border-radius: 12px;
        padding: 1.7rem;
        height: 100%;
        transition: box-shadow 0.2s ease;
    }
    .panel-putih:hover { box-shadow: 0 12px 28px -14px rgba(60,45,20,0.18); }

    @media (max-width: 767px) {
        .hero-baca { padding: 2.2rem 1.6rem; }
        .hero-baca h1 { font-size: 1.9rem; }
    }
</style>

<div class="beranda-wrap">

    <div class="hero-baca mb-4">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="hero-tag"><i class="fa-solid fa-feather-pointed"></i> Sistem Manajemen Perpustakaan</span>
                <h1>Rapikan <span class="garis-aksen">koleksi buku</span>, tanpa ribet.</h1>
                <p class="lead">
                    Satu tempat untuk mencatat, mencari, dan memperbarui data buku perpustakaan —
                    mulai dari judul, stok, sampai kategori, semuanya rapi di sini.
                </p>
                <div class="mt-4">
                    <a href="index.php" class="btn btn-baca-utama me-2">
                        <i class="fa-solid fa-list-ul me-1"></i> Lihat Daftar Buku
                    </a>
                    <?php if (isLoggedIn()): ?>
                        <a href="create.php" class="btn btn-baca-outline">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Buku
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-baca-outline">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login Admin
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-5 d-none d-md-block">
                <div class="ilustrasi-rak">
                    <div class="lencana-melayang"><i class="fa-solid fa-star"></i> <?php echo $totalBuku; ?> judul tersimpan</div>
                    <div class="tumpukan-buku">
                        <div class="balok-buku" style="background:#3f6653; width: 88%;"></div>
                        <div class="balok-buku" style="background:#a3763f; width: 100%;"></div>
                        <div class="balok-buku" style="background:#4a5d75; width: 78%;"></div>
                        <div class="balok-buku" style="background:#7a4b5a; width: 92%;"></div>
                        <div class="buku-berdiri"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rak-stat mb-4">
        <div class="item-stat">
            <div class="icon-stat" style="background:#eef2e6; color:#2c4a3b;"><i class="fa-solid fa-book"></i></div>
            <span class="angka"><?php echo $totalBuku; ?></span>
            <div class="label">Judul Buku</div>
        </div>
        <div class="pemisah d-none d-sm-block"></div>
        <div class="item-stat">
            <div class="icon-stat" style="background:#f3e6cf; color:#8a5a2e;"><i class="fa-solid fa-boxes-stacked"></i></div>
            <span class="angka"><?php echo $totalStok ?? 0; ?></span>
            <div class="label">Total Stok</div>
        </div>
        <div class="pemisah d-none d-sm-block"></div>
        <div class="item-stat">
            <div class="icon-stat" style="background:#efe3e6; color:#7a4b5a;"><i class="fa-solid fa-tags"></i></div>
            <span class="angka"><?php echo $totalKategori; ?></span>
            <div class="label">Kategori</div>
        </div>
    </div>

    <?php if (mysqli_num_rows($kategoriList) > 0): ?>
    <div class="d-flex flex-wrap gap-2 mb-4">
        <?php $j = 0; while ($k = mysqli_fetch_assoc($kategoriList)): $warnaChip = $paletteRak[$j % count($paletteRak)]; $j++; ?>
            <a href="index.php?cari=<?php echo urlencode($k['kategori']); ?>" class="chip-kategori text-decoration-none">
                <span class="titik" style="background: <?php echo $warnaChip; ?>;"></span>
                <?php echo htmlspecialchars($k['kategori']); ?>
                <span class="jumlah">&middot; <?php echo $k['jumlah']; ?></span>
            </a>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="panel-putih">
                <h2 class="panel-judul font-judul">Baru saja masuk rak</h2>
                <p class="panel-sub">Buku-buku yang terakhir ditambahkan ke katalog</p>

                <?php if (mysqli_num_rows($bukuTerbaru) > 0): $i = 0; ?>
                    <?php while ($b = mysqli_fetch_assoc($bukuTerbaru)): $warna = $paletteRak[$i % count($paletteRak)]; $i++; ?>
                        <a href="detail.php?id=<?php echo $b['id']; ?>" class="baris-rak text-decoration-none">
                            <div class="punggung-buku" style="background: <?php echo $warna; ?>;">
                                <?php if (!empty($b['cover'])): ?>
                                    <img src="uploads/covers/<?php echo htmlspecialchars($b['cover']); ?>" alt="">
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="judul-buku"><?php echo htmlspecialchars($b['judul']); ?></div>
                                <div class="meta-buku"><?php echo htmlspecialchars($b['pengarang']); ?> &middot; <?php echo htmlspecialchars($b['kategori']); ?></div>
                            </div>
                            <span class="badge-stok"><?php echo $b['stok']; ?> di rak</span>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada buku yang tercatat. Yuk, <a href="create.php">tambahkan yang pertama</a>.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel-putih">
                <h2 class="panel-judul font-judul">Yang bisa kamu lakukan</h2>
                <p class="panel-sub">Empat hal dasar buat kelola katalog</p>
                <ul class="daftar-fitur">
                    <li>
                        <span class="nomor-fitur">01</span>
                        <div>
                            <div class="judul-fitur">Tambah data buku baru</div>
                            <div class="ket-fitur">Catat judul, pengarang, dan stok dalam satu form singkat.</div>
                        </div>
                    </li>
                    <li>
                        <span class="nomor-fitur">02</span>
                        <div>
                            <div class="judul-fitur">Cari cepat</div>
                            <div class="ket-fitur">Cari berdasarkan judul atau nama pengarang.</div>
                        </div>
                    </li>
                    <li>
                        <span class="nomor-fitur">03</span>
                        <div>
                            <div class="judul-fitur">Ubah data kapan saja</div>
                            <div class="ket-fitur">Perbarui stok atau info buku saat ada perubahan.</div>
                        </div>
                    </li>
                    <li>
                        <span class="nomor-fitur">04</span>
                        <div>
                            <div class="judul-fitur">Hapus data usang</div>
                            <div class="ket-fitur">Buang data yang sudah tidak relevan lagi.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="catatan-tentang">
        <span class="tanda-kutip">&ldquo;</span>
        <strong>Tentang aplikasi ini.</strong>
        Dibangun pakai native PHP tanpa framework, dipadukan Bootstrap 5 untuk tampilan yang
        tetap enak dilihat di HP maupun laptop, dan MySQL sebagai tempat menyimpan datanya.
        Awalnya dibuat untuk tugas praktikum pemrograman web, dengan studi kasus pengelolaan
        data buku perpustakaan sehari-hari.
    </div>

</div>

<?php include "includes/footer.php"; ?>