<?= $this->extend('pelanggan/layout_plg') ?>

<?= $this->section('isi') ?>
<?php
$alamat = (array) ($alamat ?? []);
$errors = (array) (session()->getFlashdata('errors') ?? []);
$e      = static fn (string $teks): string => htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');

$ubah  = ! empty($alamat['id_alamat']);
$aksi  = $ubah
    ? base_url('alamat/update/' . $alamat['id_alamat'])
    : base_url('alamat/simpan');
$nilai = static fn (string $kunci): string => (string) old($kunci, (string) ($alamat[$kunci] ?? ''));
$utama = (string) old('is_default', (string) ($alamat['is_default'] ?? '0')) === '1';
?>

<div class="judul-halaman">
    <a href="<?= base_url('alamat') ?>" class="kembali">&larr;</a>
    <h3><?= $ubah ? 'Ubah Alamat' : 'Tambah Alamat Baru' ?></h3>
</div>

<div class="panel">
    <?php if ($errors): ?>
        <div class="alert"><?= implode('<br>', array_map($e, array_map('strval', $errors))) ?></div>
    <?php endif; ?>

    <form action="<?= $e($aksi) ?>" method="post" class="form">
        <?= csrf_field() ?>

        <label>Label Alamat</label>
        <input type="text" name="label" placeholder="Contoh: Rumah, Kantor, Kos" value="<?= $e($nilai('label')) ?>">

        <label>Nama Penerima</label>
        <input type="text" name="nama_penerima" placeholder="Nama penerima" value="<?= $e($nilai('nama_penerima')) ?>">

        <label>Nomor Telepon</label>
        <input type="text" name="no_hp" placeholder="08XXXXXXXXXX" value="<?= $e($nilai('no_hp')) ?>">

        <label>Alamat Lengkap</label>
        <textarea name="alamat_lengkap" rows="4" placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kota"><?= $e($nilai('alamat_lengkap')) ?></textarea>

        <label>Catatan untuk Kurir (opsional)</label>
        <textarea name="catatan_kurir" rows="2" placeholder="Contoh: Pagar warna hijau, titip ke satpam"><?= $e($nilai('catatan_kurir')) ?></textarea>

        <label class="check">
            <input type="checkbox" name="is_default" value="1" <?= $utama ? 'checked' : '' ?>>
            Jadikan alamat utama
        </label>

        <button type="submit" class="btn btn-dark btn-small">Simpan</button>
    </form>
</div>
<?= $this->endSection() ?>