<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfCheck() {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        die("Token keamanan tidak valid. Silakan muat ulang halaman dan coba lagi.");
    }
}

function prosesUploadCover($file) {
    $izinkanTipe = ['image/jpeg', 'image/png', 'image/webp'];
    $maxUkuran = 2 * 1024 * 1024; // 2MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Upload gagal, coba lagi.', 'filename' => null];
    }
    if (!in_array(mime_content_type($file['tmp_name']), $izinkanTipe)) {
        return ['error' => 'Format cover harus JPG, PNG, atau WEBP.', 'filename' => null];
    }
    if ($file['size'] > $maxUkuran) {
        return ['error' => 'Ukuran cover maksimal 2MB.', 'filename' => null];
    }

    $folderTujuan = __DIR__ . '/../uploads/covers/';
    if (!is_dir($folderTujuan)) {
        mkdir($folderTujuan, 0755, true);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $namaBaru = uniqid('cover_', true) . '.' . $ext;

    if (!move_uploaded_file($file['tmp_name'], $folderTujuan . $namaBaru)) {
        return ['error' => 'Gagal menyimpan file cover.', 'filename' => null];
    }

    return ['error' => null, 'filename' => $namaBaru];
}

function hapusCoverLama($namaFile) {
    $path = __DIR__ . '/../uploads/covers/' . $namaFile;
    if ($namaFile && file_exists($path)) {
        unlink($path);
    }
}