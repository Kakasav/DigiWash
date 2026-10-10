<?php
$nama    = (string) session()->get('nama');
$judul   = (string) ($judul ?? 'Digiwash');
$e       = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : '?';
?>
<?php helper('foto'); $fotoHeader = foto_profil(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash - <?= $e($judul) ?></title>
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
<div class="avatar" style="overflow:hidden">
    <?php if ($fotoHeader): ?>
        <img src="<?= $e($fotoHeader) ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
    <?php else: ?>
        <?= $e($inisial) ?>
    <?php endif; ?>
</div>
        </div>

        <div class="kolom">
            <?= $this->renderSection('isi') ?>
        </div>

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
    document.querySelectorAll('a[data-konfirmasi]').forEach(function (el) {
        el.addEventListener('click', function (ev) {
            if (!confirm(el.getAttribute('data-konfirmasi'))) ev.preventDefault();
        });
    });
    document.querySelectorAll('[data-toggle-pw]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-toggle-pw'));
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
})();
</script>
</body>
</html>