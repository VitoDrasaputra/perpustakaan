<footer class="mt-5 pt-4" style="border-top: 1px solid var(--border-lembut);">
        <div class="row gy-3 pb-4">
            <div class="col-md-5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="logo-mark" style="width:28px;height:28px;font-size:0.8rem;"><i class="fa-solid fa-feather-pointed"></i></span>
                    <span class="font-judul" style="font-weight:600; color: var(--teks-gelap);">Perpustakaan Digital</span>
                </div>
                <p class="nama-sub mb-0" style="max-width: 320px;">Aplikasi sederhana untuk mencatat dan mengelola koleksi buku perpustakaan, dibangun dengan PHP native dan MySQL.</p>
            </div>
            <div class="col-md-3">
                <div class="fw-semibold mb-2" style="color: var(--teks-gelap); font-size: 0.9rem;">Navigasi</div>
                <ul class="list-unstyled d-flex flex-column gap-1">
                    <li><a href="home.php" class="nama-sub text-decoration-none">Home</a></li>
                    <li><a href="index.php" class="nama-sub text-decoration-none">Daftar Buku</a></li>
                    <li><a href="create.php" class="nama-sub text-decoration-none">Tambah Buku</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <div class="fw-semibold mb-2" style="color: var(--teks-gelap); font-size: 0.9rem;">Dibangun dengan</div>
                <p class="nama-sub mb-0">PHP &middot; MySQL &middot; Bootstrap 5</p>
            </div>
        </div>
        <div class="text-center py-3" style="border-top: 1px solid var(--border-lembut); color: var(--teks-muted);">
            <small>&copy; <?php echo date("Y"); ?> Perpustakaan Digital &middot; dibuat untuk praktikum pemrograman web</small>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>