<?php
include "includes/koneksi.php";

$keyword   = isset($_GET['cari']) ? trim($_GET['cari']) : "";
$kategoriF = isset($_GET['kategori']) ? trim($_GET['kategori']) : "";

$where = [];
$params = [];
$types = "";

if ($keyword != "") {
    $where[] = "(judul LIKE ? OR pengarang LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
    $types .= "ss";
}
if ($kategoriF != "") {
    $where[] = "kategori = ?";
    $params[] = $kategoriF;
    $types .= "s";
}
$klausaWhere = count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT judul, pengarang, penerbit, tahun_terbit, kategori, stok, created_at FROM buku" . $klausaWhere . " ORDER BY id ASC";
$stmt = mysqli_prepare($conn, $sql);
if ($types) mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_buku_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF");
fputcsv($output, ['Judul', 'Pengarang', 'Penerbit', 'Tahun Terbit', 'Kategori', 'Stok', 'Ditambahkan']);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit;