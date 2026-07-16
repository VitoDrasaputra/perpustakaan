<?php
include "includes/koneksi.php";
include "includes/auth.php";
requireLogin();

$error = "";

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: index.php");
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : intval($_GET['id']);

function ambilBuku($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

$data = ambilBuku($conn, $id);
if (!$data) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    csrfCheck();

    $judul = trim($_POST['judul']);
    $pengarang = trim($_POST['pengarang']);
    $penerbit = trim($_POST['penerbit']);
    $tahun_terbit = intval($_POST['tahun_terbit']);
    $kategori = trim($_POST['kategori']);
    $stok = intval($_POST['stok']);
    $cover = $data['cover'];

    if (empty($judul) || empty($pengarang) || empty($penerbit)) {
        $error = "Judul, pengarang, dan penerbit wajib diisi.";
        $data = array_merge($data, $_POST);
    } else {
        if (!empty($_FILES['cover']['name'])) {
            $uploadResult = prosesUploadCover($_FILES['cover']);
            if ($uploadResult['error']) {
                $error = $uploadResult['error'];
                $data = array_merge($data, $_POST);
            } else {
                hapusCoverLama($data['cover']);
                $cover = $uploadResult['filename'];
            }
        }

        if (!$error) {
            $stmt = mysqli_prepare($conn,
                "UPDATE buku SET judul=?, pengarang=?, penerbit=?, tahun_terbit=?, kategori=?, stok=?, cover=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "sssisisi", $judul, $pengarang, $penerbit, $tahun_terbit, $kategori, $stok, $cover, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php?status=ubah");
                exit;
            } else {
                $error = "Gagal memperbarui data: " . mysqli_error($conn);
            }
        }
    }
}

$judulHalaman = "Edit Buku";
$breadcrumb = [
    ['label' => 'Home', 'url' => 'home.php'],
    ['label' => 'Daftar Buku', 'url' => 'index.php'],
    ['label' => 'Edit: ' . $data['judul']],
];
include "includes/header.php";
?>

<div class="card p-4 p-md-5" style="max-width: 680px; margin: 0 auto;">
    <div class="kop-form">
        <span class="ikon-kop-form"><i class="fa-solid fa-pen"></i></span>
        <div>
            <h4 class="mb-0 font-judul">Edit Data Buku</h4>
            <div class="nama-sub">Perbarui informasi "<?php echo htmlspecialchars($data['judul']); ?>"</div>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-perpus-hapus"><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="update.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" required value="<?php echo htmlspecialchars($data['judul']); ?>">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Pengarang</label>
                <input type="text" name="pengarang" class="form-control" required value="<?php echo htmlspecialchars($data['pengarang']); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control" required value="<?php echo htmlspecialchars($data['penerbit']); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" min="1900" max="<?php echo date('Y'); ?>" required value="<?php echo htmlspecialchars($data['tahun_terbit']); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" required value="<?php echo htmlspecialchars($data['stok']); ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select">
                <?php
                $kategoriList = ["Novel", "Sejarah", "Pengembangan Diri", "Sains", "Teknologi", "Lainnya"];
                foreach ($kategoriList as $k) {
                    $selected = ($data['kategori'] == $k) ? "selected" : "";
                    echo "<option value=\"" . htmlspecialchars($k) . "\" $selected>" . htmlspecialchars($k) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Cover Buku</label>
            <?php if (!empty($data['cover'])): ?>
                <div class="mb-2">
                    <img src="uploads/covers/<?php echo htmlspecialchars($data['cover']); ?>" style="height:90px; border-radius:8px;">
                </div>
            <?php endif; ?>
            <input type="file" name="cover" class="form-control" accept="image/jpeg,image/png,image/webp">
            <div class="nama-sub mt-1">Kosongkan jika tidak ingin mengganti cover.</div>
        </div>
        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-batal"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
            <button type="submit" class="btn btn-simpan"><i class="fa-solid fa-check me-1"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include "includes/footer.php"; ?>