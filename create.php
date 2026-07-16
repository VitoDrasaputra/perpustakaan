<?php
include "includes/koneksi.php";
include "includes/auth.php";
requireLogin();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    csrfCheck();

    $judul = trim($_POST['judul']);
    $pengarang = trim($_POST['pengarang']);
    $penerbit = trim($_POST['penerbit']);
    $tahun_terbit = intval($_POST['tahun_terbit']);
    $kategori = trim($_POST['kategori']);
    $stok = intval($_POST['stok']);
    $cover = null;

    if (empty($judul) || empty($pengarang) || empty($penerbit)) {
        $error = "Judul, pengarang, dan penerbit wajib diisi.";
    } else {
        if (!empty($_FILES['cover']['name'])) {
            $uploadResult = prosesUploadCover($_FILES['cover']);
            if ($uploadResult['error']) {
                $error = $uploadResult['error'];
            } else {
                $cover = $uploadResult['filename'];
            }
        }

        if (!$error) {
            $stmt = mysqli_prepare($conn,
                "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, kategori, stok, cover)
                 VALUES (?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssisis", $judul, $pengarang, $penerbit, $tahun_terbit, $kategori, $stok, $cover);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php?status=tambah");
                exit;
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}

$judulHalaman = "Tambah Buku";
$breadcrumb = [
    ['label' => 'Home', 'url' => 'home.php'],
    ['label' => 'Daftar Buku', 'url' => 'index.php'],
    ['label' => 'Tambah Buku'],
];
include "includes/header.php";
?>

<div class="card p-4 p-md-5" style="max-width: 680px; margin: 0 auto;">
    <div class="kop-form">
        <span class="ikon-kop-form"><i class="fa-solid fa-plus"></i></span>
        <div>
            <h4 class="mb-0 font-judul">Tambah Buku Baru</h4>
            <div class="nama-sub">Catat satu judul baru ke katalog perpustakaan</div>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-perpus-hapus"><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="create.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>">

        <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" placeholder="cth. Laskar Pelangi" required value="<?php echo isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : ''; ?>">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Pengarang</label>
                <input type="text" name="pengarang" class="form-control" placeholder="Nama penulis" required value="<?php echo isset($_POST['pengarang']) ? htmlspecialchars($_POST['pengarang']) : ''; ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control" placeholder="Nama penerbit" required value="<?php echo isset($_POST['penerbit']) ? htmlspecialchars($_POST['penerbit']) : ''; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" min="1900" max="<?php echo date('Y'); ?>" required value="<?php echo isset($_POST['tahun_terbit']) ? htmlspecialchars($_POST['tahun_terbit']) : date('Y'); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" required value="<?php echo isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : '0'; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select">
                <option value="Novel">Novel</option>
                <option value="Sejarah">Sejarah</option>
                <option value="Pengembangan Diri">Pengembangan Diri</option>
                <option value="Sains">Sains</option>
                <option value="Teknologi">Teknologi</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Cover Buku (opsional, JPG/PNG/WEBP, maks 2MB)</label>
            <input type="file" name="cover" class="form-control" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-batal"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
            <button type="submit" class="btn btn-simpan"><i class="fa-solid fa-check me-1"></i> Simpan Buku</button>
        </div>
    </form>
</div>

<?php include "includes/footer.php"; ?>