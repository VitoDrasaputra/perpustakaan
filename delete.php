<?php
include "includes/koneksi.php";
include "includes/auth.php";
requireLogin();

if (isset($_GET['id']) && isset($_GET['token']) && hash_equals(csrfToken(), $_GET['token'])) {
    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, "SELECT cover FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($row) {
        hapusCoverLama($row['cover']);
    }

    $stmt2 = mysqli_prepare($conn, "DELETE FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
}

header("Location: index.php?status=hapus");
exit;