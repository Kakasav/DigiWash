<?php
$daftar = (array) ($daftar ?? []);
$nama   = (string) session()->get('nama');

$errors = (array) (session()->getFlashdata('errors') ?? []);
$sukses = (string) (session()->getFlashdata('success') ?? '');

$e       = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : '?';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash - Alamat Saya</title>
    <link rel="stylesheet" href="<?= base_url('css/digiwash.css') ?>">
</head>
<body>
<div class="dash-wrap">
    <div class="dash">

        <div class="dash-head">
            <div class="logo">🧺 DIGIWASH</div>
            <nav class="bottom-nav">
                <a href="<?= base_url('dashboard') ?>"><span>🏠</span>Beranda</a>
                <a href="#" data-soon><span>🧺</span>Pesanan</a>
                <a href="#" data-soon><span>🔔</span>Notifikasi</a>
                <a href="<?= base_url('profil') ?>" class="active"><span>👤</span>Akun</a>
            </nav>
            <div class="avatar"><?= $e($inisial) ?></div>
        </div>

        <div class="judul-halaman">
            <a href="<?= base_url('profil/data') ?>" class="kembali">&larr;</a>
            <h3>Alamat Saya</h3>
        </div>

        <?php if ($sukses !== ''): ?>
            <div class="alert ok"><?= $e($sukses) ?></div>
        <?php endif; ?>
        <?php if ($errors): ?>
            <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
        <?php endif; ?>

        <?php if (! $daftar): ?>
            <div class="panel kosong">
                <p>Belum ada alamat tersimpan.</p>
            </div>
        <?php endif; ?>

        <div class="alamat-list">
            <?php foreach ($daftar as $a): ?>
                <div class="alamat-card<?= (int) $a['is_default'] === 1 ? ' utama' : '' ?>">
                    <div class="alamat-top">
                        <strong><?= $e((string) $a['label']) ?></strong>
                        <?php if ((int) $a['is_default'] === 1): ?>
                            <span class="tag">Utama</span>
                        <?php endif; ?>
                    </div>
                    <p class="penerima"><?= $e((string) $a['nama_penerima']) ?> &middot; <?= $e((string) $a['no_hp']) ?></p>
                    <p class="detail"><?= nl2br($e((string) $a['alamat_lengkap'])) ?></p>
                    <?php if (! empty($a['catatan_kurir'])): ?>
                        <p class="catatan">Catatan: <?= $e((string) $a['catatan_kurir']) ?></p>
                    <?php endif; ?>

                    <div class="alamat-aksi">
                        <a href="<?= base_url('alamat/ubah/' . $a['id_alamat']) ?>">Ubah</a>

                        <?php if ((int) $a['is_default'] !== 1): ?>
                            <form action="<?= base_url('alamat/utama/' . $a['id_alamat']) ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit">Jadikan Utama</button>
                            </form>
                        <?php endif; ?>

                        <form action="<?= base_url('alamat/hapus/' . $a['id_alamat']) ?>" method="post" data-konfirmasi="Hapus alamat ini?">
                            <?= csrf_field() ?>
                            <button type="submit" class="hapus">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="<?= base_url('alamat/tambah') ?>" class="btn btn-dark btn-tambah">+ Tambah Alamat Baru</a>

    </div>
</div>

<div id="toast" class="toast" role="status" aria-live="polite"></div>

<script>
(function () {
    var toast = document.getElementById('toast'), timer;
    function tampilkan(p) {
        toast.textContent = p; toast.classList.add('show');
        clearTimeout(timer);
        timer = setTimeout(function () { toast.classList.remove('show'); }, 2000);
    }
    document.querySelectorAll('[data-soon]').forEach(function (el) {
        el.addEventListener('click', function (ev) { ev.preventDefault(); tampilkan('Fitur segera hadir'); });
    });
    document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
        f.addEventListener('submit', function (ev) {
            if (!confirm(f.getAttribute('data-konfirmasi'))) ev.preventDefault();
        });
    });
})();
</script>
</body>
</html>