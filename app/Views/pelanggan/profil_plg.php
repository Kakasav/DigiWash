<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
helper('foto');

$user   = (array) ($user ?? []);
$nama   = (string) ($user['nama'] ?? '');
$noHp   = (string) ($user['no_hp'] ?? '');
$sukses = (string) (session()->getFlashdata('success') ?? '');
$errors = (array) (session()->getFlashdata('errors') ?? []);

$e       = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
$inisial = $nama !== '' ? strtoupper(substr($nama, 0, 1)) : '?';
$foto    = foto_profil();
?>

<div class="judul-halaman">
    <a href="<?= base_url('dashboard') ?>" class="kembali">&larr;</a>
    <h3>Profil Saya</h3>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>
<?php if ($errors): ?>
    <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
<?php endif; ?>

<div class="profil-hero">
    <form action="<?= base_url('profil/foto') ?>" method="post" enctype="multipart/form-data" id="formFoto">
        <?= csrf_field() ?>
        <label class="foto-wrap" for="inputFoto" title="Ganti foto profil" style="position:relative;display:block;width:96px;height:96px;margin:0 auto 12px;cursor:pointer">
    <?php if ($foto): ?>
        <img src="<?= $e($foto) ?>" alt="Foto profil" class="hero-foto" style="width:96px;height:96px;border-radius:50%;object-fit:cover;display:block">
    <?php else: ?>
        <div class="hero-avatar"><?= $e($inisial) ?></div>
    <?php endif; ?>
    <span class="foto-edit" style="position:absolute;right:0;bottom:0;width:30px;height:30px;border-radius:50%;background:#0b3d5c;border:2px solid #fff;display:grid;place-items:center;font-size:14px">📷</span>
</label>
        <input type="file" name="foto" id="inputFoto" accept="image/png,image/jpeg,image/webp" hidden>
    </form>

    <h3><?= $e($nama) ?></h3>
    <p><?= $e($noHp) ?></p>

    <?php if ($foto): ?>
        <form action="<?= base_url('profil/foto/hapus') ?>" method="post" data-konfirmasi="Hapus foto profil?">
            <?= csrf_field() ?>
            <button type="submit" class="hapus-foto">Hapus foto</button>
        </form>
    <?php endif; ?>
</div>

<div class="daftar-baris">
    <a class="baris" href="<?= base_url('profil/data') ?>"><span class="ikon">👤</span><span class="teks">Data Pribadi</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="<?= base_url('profil/password') ?>"><span class="ikon">🔒</span><span class="teks">Ganti Kata Sandi</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="<?= base_url('profil/bantuan') ?>"><span class="ikon">❓</span><span class="teks">Bantuan</span><span class="panah">&rsaquo;</span></a>
    <a class="baris" href="<?= base_url('profil/tentang') ?>"><span class="ikon">ℹ️</span><span class="teks">Tentang Aplikasi</span><span class="panah">&rsaquo;</span></a>
</div>

<a href="<?= base_url('keluar') ?>" class="btn-logout" data-konfirmasi="Yakin ingin keluar?">Logout</a>

<script>
(function () {
    var input = document.getElementById('inputFoto');
    input.addEventListener('change', function () {
        var f = input.files[0];
        if (!f) return;
        if (f.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB.');
            input.value = '';
            return;
        }
        document.getElementById('formFoto').submit();
    });

    document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
        f.addEventListener('submit', function (ev) {
            if (!confirm(f.getAttribute('data-konfirmasi'))) ev.preventDefault();
        });
    });
})();
</script>
<?= $this->endSection() ?>