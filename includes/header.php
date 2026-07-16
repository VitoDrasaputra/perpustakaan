<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($judulHalaman) ? htmlspecialchars($judulHalaman) . " · Perpustakaan Digital" : "Perpustakaan Digital"; ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2222%22 fill=%22%232c4a3b%22/><path d=%22M28 30h44v42a4 4 0 0 1-4 4H32a4 4 0 0 1-4-4V30z%22 fill=%22none%22 stroke=%22%23f3e6cf%22 stroke-width=%224%22/><line x1=%2250%22 y1=%2230%22 x2=%2250%22 y2=%2276%22 stroke=%22%23f3e6cf%22 stroke-width=%224%22/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --hijau-tua: #2c4a3b;
            --hijau-gelap: #21382d;
            --emas: #c7ab7a;
            --emas-tua: #8a5a2e;
            --krem: #fbf7ef;
            --krem-chip: #f3e6cf;
            --teks-gelap: #2c2620;
            --teks-body: #4a4238;
            --teks-muted: #8a8072;
            --border-lembut: #ece6da;
            --merah-lembut: #a8493f;
            --merah-bg: #f7e9e7;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--teks-body);
            background-color: #f4f1ea;
            background-image: radial-gradient(#e2d8c2 0.6px, transparent 0.6px);
            background-size: 18px 18px;
            background-attachment: fixed;
        }
        h1, h2, h3, h4, h5, .font-judul { font-family: 'Fraunces', serif; color: var(--teks-gelap); }

        a { color: var(--hijau-tua); }
        a:hover { color: var(--hijau-gelap); }

        .navbar-perpus {
            background: #fffdf9;
            border-bottom: 1px solid var(--border-lembut);
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }
        .navbar-perpus .navbar-brand {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            color: var(--teks-gelap) !important;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 1.2rem;
        }
        .logo-mark {
            width: 34px; height: 34px;
            background: var(--hijau-tua);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #f3e6cf;
            font-size: 0.95rem;
            transform: rotate(-3deg);
        }
        .navbar-perpus .nav-link {
            color: var(--teks-muted) !important;
            font-weight: 500;
            font-size: 0.93rem;
            padding: 0.5rem 0.9rem !important;
            position: relative;
            margin: 0 0.1rem;
        }
        .navbar-perpus .nav-link i { margin-right: 0.35rem; font-size: 0.85rem; }
        .navbar-perpus .nav-link:hover { color: var(--teks-gelap) !important; }
        .navbar-perpus .nav-link.active {
            color: var(--hijau-tua) !important;
            font-weight: 600;
        }
        .navbar-perpus .nav-link.active::after {
            content: "";
            position: absolute;
            left: 0.9rem; right: 0.9rem; bottom: -1px;
            height: 2px;
            background: var(--emas);
            border-radius: 2px;
        }
        .navbar-toggler { border: 1px solid var(--border-lembut) !important; box-shadow: none !important; }

        .card {
            border: 1px solid var(--border-lembut);
            box-shadow: 0 10px 26px -16px rgba(60,45,20,0.18);
            border-radius: 14px;
        }

        .table-perpus thead {
            background: transparent;
        }
        .table-perpus thead th {
            color: var(--teks-muted);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            border-bottom: 1.5px solid var(--border-lembut);
            padding-bottom: 0.8rem;
        }
        .table-perpus tbody td {
            border-color: var(--border-lembut);
            vertical-align: middle;
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }
        .table-perpus tbody tr { transition: background 0.15s ease; }
        .table-perpus tbody tr:hover { background: var(--krem); }

        .chip-nomor {
            width: 26px; height: 26px;
            border-radius: 50%;
            background: var(--krem);
            color: var(--emas-tua);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .nama-judul { font-weight: 600; color: var(--teks-gelap); }
        .nama-sub { font-size: 0.8rem; color: var(--teks-muted); }

        .badge-kategori {
            background: var(--krem-chip);
            color: var(--emas-tua);
            font-weight: 500;
            font-size: 0.76rem;
            padding: 0.4rem 0.65rem;
            border-radius: 20px;
        }
        .badge-stok-ada {
            background: #eaf1e6;
            color: #3f6b3f;
            font-weight: 500;
            font-size: 0.76rem;
            padding: 0.4rem 0.65rem;
            border-radius: 20px;
        }
        .badge-stok-habis {
            background: var(--merah-bg);
            color: var(--merah-lembut);
            font-weight: 500;
            font-size: 0.76rem;
            padding: 0.4rem 0.65rem;
            border-radius: 20px;
        }

        .btn-aksi-edit, .btn-aksi-hapus {
            border: none;
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .btn-aksi-edit { background: var(--krem-chip); color: var(--emas-tua); }
        .btn-aksi-edit:hover { background: #eddcb8; color: var(--emas-tua); }
        .btn-aksi-hapus { background: var(--merah-bg); color: var(--merah-lembut); }
        .btn-aksi-hapus:hover { background: #f0d6d2; color: var(--merah-lembut); }

        .form-label {
            font-weight: 600;
            color: var(--teks-gelap);
            font-size: 0.88rem;
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            border: 1.5px solid var(--border-lembut);
            border-radius: 8px;
            padding: 0.55rem 0.85rem;
            font-size: 0.94rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--hijau-tua);
            box-shadow: 0 0 0 3px rgba(44,74,59,0.12);
        }

        .btn-simpan {
            background: var(--hijau-tua);
            border: none;
            color: #fff;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .btn-simpan:hover { background: var(--hijau-gelap); color: #fff; transform: translateY(-1px); }
        .btn-batal {
            border: 1.5px solid var(--border-lembut);
            color: var(--teks-body);
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            font-weight: 500;
            background: #fff;
        }
        .btn-batal:hover { background: var(--krem); color: var(--teks-gelap); }

        .kop-form {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1.6rem;
        }
        .ikon-kop-form {
            width: 42px; height: 42px;
            border-radius: 10px;
            background: var(--krem);
            color: var(--hijau-tua);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }

        .kotak-cari {
            border: 1.5px solid var(--border-lembut);
            border-radius: 10px;
            padding: 0.5rem 0.9rem;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 260px;
        }
        .kotak-cari input {
            border: none;
            outline: none;
            font-size: 0.9rem;
            flex: 1;
            background: transparent;
        }
        .kotak-cari i { color: var(--teks-muted); }

        .breadcrumb-perpus {
            font-size: 0.85rem;
            color: var(--teks-muted);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .breadcrumb-perpus a { color: var(--teks-muted); text-decoration: none; }
        .breadcrumb-perpus a:hover { color: var(--hijau-tua); }
        .breadcrumb-perpus .pemisah-breadcrumb { margin: 0 0.5rem; color: #d6cdb9; }
        .breadcrumb-perpus .aktif-breadcrumb { color: var(--teks-gelap); font-weight: 500; }

        .pagination-perpus {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }
        .pagination-perpus a, .pagination-perpus span {
            min-width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
            text-decoration: none;
            color: var(--teks-body);
            border: 1px solid var(--border-lembut);
            background: #fff;
        }
        .pagination-perpus a:hover { background: var(--krem); color: var(--teks-gelap); }
        .pagination-perpus .aktif-hal { background: var(--hijau-tua); color: #fff; border-color: var(--hijau-tua); }
        .pagination-perpus .nonaktif-hal { opacity: 0.4; pointer-events: none; }

        .sampul-buku {
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.92);
            font-family: 'Fraunces', serif;
            font-weight: 600;
            box-shadow: 0 10px 22px -10px rgba(0,0,0,0.35);
            position: relative;
            overflow: hidden;
        }
        .sampul-buku::after {
            content: "";
            position: absolute; left: 14%; top: 0; bottom: 0; width: 3px;
            background: rgba(255,255,255,0.25);
        }

        .alert-perpus-sukses {
            background: #eaf1e6;
            border: 1px solid #cfe2c6;
            color: #3f6b3f;
            border-radius: 10px;
        }
        .alert-perpus-hapus {
            background: var(--merah-bg);
            border: 1px solid #edc9c3;
            color: var(--merah-lembut);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-perpus">
    <div class="container">
        <a class="navbar-brand" href="home.php">
            <span class="logo-mark"><i class="fa-solid fa-feather-pointed"></i></span>
            Perpustakaan Digital
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>" href="home.php"><i class="fa-solid fa-house"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'detail.php', 'update.php']) ? 'active' : ''; ?>" href="index.php"><i class="fa-solid fa-list"></i>Daftar Buku</a>
                </li>
                <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'create.php') ? 'active' : ''; ?>" href="create.php"><i class="fa-solid fa-plus"></i>Tambah Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : ''; ?>" href="login.php"><i class="fa-solid fa-right-to-bracket"></i>Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-4">
<?php if (!empty($breadcrumb)): ?>
<nav class="breadcrumb-perpus mb-3" aria-label="breadcrumb">
    <?php $totalCrumb = count($breadcrumb); foreach ($breadcrumb as $i => $crumb): ?>
        <?php if ($i > 0): ?><span class="pemisah-breadcrumb">/</span><?php endif; ?>
        <?php if (!empty($crumb['url']) && $i < $totalCrumb - 1): ?>
            <a href="<?php echo $crumb['url']; ?>"><?php echo htmlspecialchars($crumb['label']); ?></a>
        <?php else: ?>
            <span class="aktif-breadcrumb"><?php echo htmlspecialchars($crumb['label']); ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>
<?php endif; ?>