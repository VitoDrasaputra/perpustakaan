<?php
include "includes/koneksi.php";
include "includes/auth.php";

$keyword   = isset($_GET['cari']) ? trim($_GET['cari']) : "";
$kategoriF = isset($_GET['kategori']) ? trim($_GET['kategori']) : "";
$urut      = isset($_GET['urut']) ? $_GET['urut'] : "terbaru";

$halaman   = isset($_GET['halaman']) ? max(1, intval($_GET['halaman'])) : 1;
$perHalaman = 6;
$offset    = ($halaman - 1) * $perHalaman;

$where = [];
$params = [];
$types = "";

if ($keyword != "") {
    $where[] = "(judul LIKE ? OR pengarang LIKE ?)";
    $likeKeyword = "%$keyword%";
    $params[] = $likeKeyword;
    $params[] = $likeKeyword;
    $types .= "ss";
}
if ($kategoriF != "") {
    $where[] = "kategori = ?";
    $params[] = $kategoriF;
    $types .= "s";
}
$klausaWhere = count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "";

switch ($urut) {
    case "judul_az": $orderBy = "judul ASC"; break;
    case "tahun_baru": $orderBy = "tahun_terbit DESC"; break;
    case "stok_banyak": $orderBy = "stok DESC"; break;
    default: $orderBy = "id DESC"; $urut = "terbaru";
}

$stmtCount = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM buku" . $klausaWhere);
if ($types) mysqli_stmt_bind_param($stmtCount, $types, ...$params);
mysqli_stmt_execute($stmtCount);
$totalData = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtCount))['total'];
$totalHalaman = max(1, ceil($totalData / $perHalaman));
if ($halaman > $totalHalaman) { $halaman = $totalHalaman; $offset = ($halaman - 1) * $perHalaman; }

$sql = "SELECT * FROM buku" . $klausaWhere . " ORDER BY $orderBy LIMIT " . intval($perHalaman) . " OFFSET " . intval($offset);
$stmt = mysqli_prepare($conn, $sql);
if ($types) mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$daftarKategori = mysqli_query($conn, "SELECT DISTINCT kategori FROM buku ORDER BY kategori ASC");

$notif = isset($_GET['status']) ? $_GET['status'] : "";

function buatUrl($ganti = []) {
    $param = array_merge($_GET, $ganti);
    foreach ($param as $k => $v) { if ($v === "" || $v === null) unset($param[$k]); }
    return "index.php?" . http_build_query($param);
}

$judulHalaman = "Daftar Buku";
$breadcrumb = [
    ['label' => 'Home', 'url' => 'home.php'],
    ['label' => 'Daftar Buku'],
];
include "includes/header.php";
?>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
        <div>
            <h4 class="mb-1 font-judul"><i class="fa-solid fa-book-open me-2"></i>Daftar Buku</h4>
            <div class="nama-sub">
                <?php echo $totalData; ?> judul <?php echo ($keyword != "" || $kategoriF != "") ? "cocok dengan filter" : "tercatat di katalog"; ?>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="export.php?<?php echo http_build_query(['cari' => $keyword, 'kategori' => $kategoriF]); ?>" class="btn btn-batal">
                <i class="fa-solid fa-file-export me-1"></i> Export CSV
            </a>
            <?php if (isLoggedIn()): ?>
                <a href="create.php" class="btn btn-simpan"><i class="fa-solid fa-plus me-1"></i> Tambah Buku</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($notif == "tambah"): ?>
        <div class="alert alert-perpus-sukses"><i class="fa-solid fa-circle-check me-2"></i>Buku berhasil ditambahkan ke katalog.</div>
    <?php elseif ($notif == "ubah"): ?>
        <div class="alert alert-perpus-sukses"><i class="fa-solid fa-circle-check me-2"></i>Data buku berhasil diperbarui.</div>
    <?php elseif ($notif == "hapus"): ?>
        <div class="alert alert-perpus-hapus"><i class="fa-solid fa-circle-exclamation me-2"></i>Buku sudah dihapus dari katalog.</div>
    <?php endif; ?>

    <form method="GET" action="index.php" class="row g-2 align-items-center mb-4">
        <div class="col-md-5">
            <div class="kotak-cari">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="cari" placeholder="Cari judul atau pengarang..." value="<?php echo htmlspecialchars($keyword); ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php mysqli_data_seek($daftarKategori, 0); while ($k = mysqli_fetch_assoc($daftarKategori)): ?>
                    <option value="<?php echo htmlspecialchars($k['kategori']); ?>" <?php echo $kategoriF == $k['kategori'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($k['kategori']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="urut" class="form-select" onchange="this.form.submit()">
                <option value="terbaru" <?php echo $urut == 'terbaru' ? 'selected' : ''; ?>>Terbaru ditambahkan</option>
                <option value="judul_az" <?php echo $urut == 'judul_az' ? 'selected' : ''; ?>>Judul A-Z</option>
                <option value="tahun_baru" <?php echo $urut == 'tahun_baru' ? 'selected' : ''; ?>>Tahun terbit terbaru</option>
                <option value="stok_banyak" <?php echo $urut == 'stok_banyak' ? 'selected' : ''; ?>>Stok terbanyak</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-batal w-100" type="submit"><i class="fa-solid fa-filter"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-perpus align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 46px;">#</th>
                    <th>Buku</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $offset + 1; ?>
                <?php if ($totalData > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span class="chip-nomor"><?php echo $no++; ?></span></td>
                            <td>
                                <a href="detail.php?id=<?php echo $row['id']; ?>" class="text-decoration-none d-flex align-items-center gap-2">
                                    <?php if (!empty($row['cover'])): ?>
                                        <img src="uploads/covers/<?php echo htmlspecialchars($row['cover']); ?>" style="width:32px;height:44px;object-fit:cover;border-radius:4px;">
                                    <?php endif; ?>
                                    <div>
                                        <div class="nama-judul"><?php echo htmlspecialchars($row['judul']); ?></div>
                                        <div class="nama-sub"><?php echo htmlspecialchars($row['pengarang']); ?></div>
                                    </div>
                                </a>
                            </td>
                            <td class="nama-sub"><?php echo htmlspecialchars($row['penerbit']); ?></td>
                            <td class="nama-sub"><?php echo htmlspecialchars($row['tahun_terbit']); ?></td>
                            <td><span class="badge-kategori"><?php echo htmlspecialchars($row['kategori']); ?></span></td>
                            <td>
                                <?php if ($row['stok'] > 0): ?>
                                    <span class="badge-stok-ada"><?php echo $row['stok']; ?> tersedia</span>
                                <?php else: ?>
                                    <span class="badge-stok-habis">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn-aksi-edit d-inline-flex align-items-center" style="background:#eef2e6; color:#3f6b3f;">
                                    <i class="fa-solid fa-eye me-1"></i> Lihat
                                </a>
                                <?php if (isLoggedIn()): ?>
                                    <a href="update.php?id=<?php echo $row['id']; ?>" class="btn-aksi-edit d-inline-flex align-items-center">
                                        <i class="fa-solid fa-pen me-1"></i> Edit
                                    </a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>&token=<?php echo csrfToken(); ?>" class="btn-aksi-hapus d-inline-flex align-items-center"
                                       onclick="return confirm('Yakin ingin menghapus buku ini?');">
                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fa-solid fa-box-open mb-2" style="font-size: 1.8rem; color: var(--teks-muted);"></i>
                            <div class="nama-sub">
                                <?php echo ($keyword != "" || $kategoriF != "") ? "Tidak ada buku yang cocok dengan filter ini." : "Belum ada data buku."; ?>
                            </div>
                            <?php if (isLoggedIn()): ?>
                                <a href="create.php" class="btn btn-simpan btn-sm mt-3"><i class="fa-solid fa-plus me-1"></i>Tambah Buku</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalHalaman > 1): ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
            <div class="nama-sub">Halaman <?php echo $halaman; ?> dari <?php echo $totalHalaman; ?></div>
            <div class="pagination-perpus">
                <a href="<?php echo buatUrl(['halaman' => $halaman - 1]); ?>" class="<?php echo $halaman <= 1 ? 'nonaktif-hal' : ''; ?>"><i class="fa-solid fa-chevron-left"></i></a>
                <?php for ($p = 1; $p <= $totalHalaman; $p++): ?>
                    <a href="<?php echo buatUrl(['halaman' => $p]); ?>" class="<?php echo $p == $halaman ? 'aktif-hal' : ''; ?>"><?php echo $p; ?></a>
                <?php endfor; ?>
                <a href="<?php echo buatUrl(['halaman' => $halaman + 1]); ?>" class="<?php echo $halaman >= $totalHalaman ? 'nonaktif-hal' : ''; ?>"><i class="fa-solid fa-chevron-right"></i></a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>