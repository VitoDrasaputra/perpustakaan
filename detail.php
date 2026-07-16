<?php
include "includes/koneksi.php";
include "includes/auth.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = mysqli_prepare($conn, "SELECT * FROM buku WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$b = mysqli_fetch_assoc($result);

$paletteSampul = ['#8a5a44', '#3f6653', '#a3763f', '#4a5d75', '#7a4b5a', '#5c6b3f'];
$warnaSampul = $paletteSampul[ord(strtoupper($b['judul'][0])) % count($paletteSampul)];

$stmtTerkait = mysqli_prepare($conn, "SELECT * FROM buku WHERE kategori = ? AND id != ? LIMIT 3");
mysqli_stmt_bind_param($stmtTerkait, "si", $b['kategori'], $id);
mysqli_stmt_execute($stmtTerkait);
$terkait = mysqli_stmt_get_result($stmtTerkait);

$judulHalaman = $b['judul'];
$breadcrumb = [
    ['label' => 'Home', 'url' => 'home.php'],
    ['label' => 'Daftar Buku', 'url' => 'index.php'],
    ['label' => $b['judul']],
];
include "includes/header.php";
?>

<div class="card p-4 p-md-5 mb-4">
    <div class="row g-4">
        <div class="col-md-3">
            <?php if (!empty($b['cover'])): ?>
                <img src="uploads/covers/<?php echo htmlspecialchars($b['cover']); ?>" style="width:100%; aspect-ratio:3/4; object-fit:cover; border-radius:10px;">
            <?php else: ?>
                <div class="sampul-buku" style="background: linear-gradient(160deg, <?php echo $warnaSampul; ?>, <?php echo $warnaSampul; ?>cc); aspect-ratio: 3/4; font-size: 2.4rem;">
                    <?php echo strtoupper(substr($b['judul'], 0, 1)); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-9">
            <span class="badge-kategori mb-2 d-inline-block"><?php echo htmlspecialchars($b['kategori']); ?></span>
            <h3 class="font-judul mb-1"><?php echo htmlspecialchars($b['judul']); ?></h3>
            <p class="nama-sub mb-4">oleh <?php echo htmlspecialchars($b['pengarang']); ?></p>

            <div class="row g-3 mb-4">
                <div class="col-sm-4">
                    <div class="nama-sub mb-1"><i class="fa-solid fa-building me-1"></i> Penerbit</div>
                    <div class="nama-judul" style="font-weight: 500;"><?php echo htmlspecialchars($b['penerbit']); ?></div>
                </div>
                <div class="col-sm-4">
                    <div class="nama-sub mb-1"><i class="fa-solid fa-calendar me-1"></i> Tahun Terbit</div>
                    <div class="nama-judul" style="font-weight: 500;"><?php echo htmlspecialchars($b['tahun_terbit']); ?></div>
                </div>
                <div class="col-sm-4">
                    <div class="nama-sub mb-1"><i class="fa-solid fa-boxes-stacked me-1"></i> Stok</div>
                    <div>
                        <?php if ($b['stok'] > 0): ?>
                            <span class="badge-stok-ada"><?php echo $b['stok']; ?> tersedia</span>
                        <?php else: ?>
                            <span class="badge-stok-habis">Habis</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($b['created_at'])): ?>
                <p class="nama-sub mb-4"><i class="fa-regular fa-clock me-1"></i> Ditambahkan ke katalog pada <?php echo date("d F Y", strtotime($b['created_at'])); ?></p>
            <?php endif; ?>

            <div class="d-flex gap-2 flex-wrap">
                <a href="index.php" class="btn btn-batal"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
                <?php if (isLoggedIn()): ?>
                    <a href="update.php?id=<?php echo $b['id']; ?>" class="btn btn-simpan"><i class="fa-solid fa-pen me-1"></i> Edit Buku</a>
                    <a href="delete.php?id=<?php echo $b['id']; ?>&token=<?php echo csrfToken(); ?>" class="btn-aksi-hapus d-inline-flex align-items-center px-3"
                       onclick="return confirm('Yakin ingin menghapus buku ini?');">
                        <i class="fa-solid fa-trash me-1"></i> Hapus
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (mysqli_num_rows($terkait) > 0): ?>
<div class="card p-4">
    <h5 class="font-judul mb-3">Buku lain di kategori "<?php echo htmlspecialchars($b['kategori']); ?>"</h5>
    <div class="row g-3">
        <?php while ($t = mysqli_fetch_assoc($terkait)): ?>
            <div class="col-md-4">
                <a href="detail.php?id=<?php echo $t['id']; ?>" class="text-decoration-none d-block p-3" style="border: 1px solid var(--border-lembut); border-radius: 10px; transition: box-shadow 0.15s ease;">
                    <div class="nama-judul mb-1"><?php echo htmlspecialchars($t['judul']); ?></div>
                    <div class="nama-sub"><?php echo htmlspecialchars($t['pengarang']); ?></div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<?php include "includes/footer.php"; ?>