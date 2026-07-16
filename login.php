<?php
include "includes/koneksi.php";
include "includes/auth.php";

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    csrfCheck();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $u = mysqli_fetch_assoc($result);

    if ($u && password_verify($password, $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['username'] = $u['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}

$judulHalaman = "Login Admin";
$breadcrumb = [];
include "includes/header.php";
?>

<div class="card p-4 p-md-5" style="max-width: 420px; margin: 0 auto;">
    <div class="kop-form">
        <span class="ikon-kop-form"><i class="fa-solid fa-lock"></i></span>
        <div>
            <h4 class="mb-0 font-judul">Login Admin</h4>
            <div class="nama-sub">Masuk untuk mengelola katalog buku</div>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-perpus-hapus"><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-simpan w-100"><i class="fa-solid fa-right-to-bracket me-1"></i> Masuk</button>
    </form>

    <div class="nama-sub mt-3 text-center">
        Akun default: <strong>admin</strong> / <strong>admin123</strong>
    </div>
</div>

<?php include "includes/footer.php"; ?>