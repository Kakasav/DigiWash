<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
$user  = (array) ($user ?? []);
$utama = isset($utama) && is_array($utama) ? $utama : null;
$sukses = (string) (session()->getFlashdata('success') ?? '');
$e      = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');

$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tgl   = '-';
if (! empty($user['tanggal_lahir'])) {
    $t = strtotime((string) $user['tanggal_lahir']);
    if ($t !== false) {
        $tgl = date('j', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}

$alamatTeks = $utama ? (string) $utama['alamat_lengkap'] : 'Belum diatur';
?>

<div class="judul-halaman">
    <a href="<?= base_url('profil') ?>" class="kembali">&larr;</a>
    <h3>Data Pribadi</h3>
</div>

<?php if ($sukses !== ''): ?>
    <div class="alert ok"><?= $e($sukses) ?></div>
<?php endif; ?>

<p class="subjudul">Informasi Akun</p>
<div class="daftar-baris">
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon">👤</span>
        <span class="teks"><small>Nama Lengkap</small><strong><?= $e((string) ($user['nama'] ?? '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon">📞</span>
        <span class="teks"><small>Nomor Telepon</small><strong><?= $e((string) ($user['no_hp'] ?? '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <div class="baris">
        <span class="ikon">✉️</span>
        <span class="teks"><small>Email</small><strong><?= $e((string) ($user['email'] ?? '-')) ?></strong></span>
    </div>
    <a class="baris" href="<?= base_url('alamat') ?>">
        <span class="ikon">📍</span>
        <span class="teks"><small>Alamat</small><strong><?= $e($alamatTeks) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon">📅</span>
        <span class="teks"><small>Tanggal Lahir</small><strong><?= $e($tgl) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
    <a class="baris" href="<?= base_url('profil/data/ubah') ?>">
        <span class="ikon">⚧</span>
        <span class="teks"><small>Jenis Kelamin</small><strong><?= $e((string) (($user['jenis_kelamin'] ?? '') ?: '-')) ?></strong></span>
        <span class="panah">&rsaquo;</span>
    </a>
</div>

<p class="subjudul">Keamanan Akun</p>
<div class="daftar-baris">
    <div class="baris">
        <span class="ikon">🛡️</span>
        <span class="teks">Verifikasi Email</span>
        <span class="badge-ok">TERVERIFIKASI</span>
    </div>
    <div class="baris">
        <span class="ikon">🛡️</span>
        <span class="teks">Verifikasi Nomor</span>
        <span class="badge-ok">TERVERIFIKASI</span>
    </div>
</div>
<?= $this->endSection() ?>