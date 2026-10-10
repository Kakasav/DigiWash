<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digiwash - Masuk Akun</title>
    <link rel="stylesheet" href="<?= base_url('css/digiwash.css') ?>">
</head>
<body>
<div class="phone auth">
    <span class="blob blob-purple" style="top:60px;right:-40px"></span>
    <span class="blob blob-pink" style="top:150px;left:-90px;width:170px;height:170px"></span>
    <span class="blob blob-green"></span>
    <a href="<?= base_url('/') ?>" class="back">&larr;</a>

    <h2 class="auth-title" style="margin-top:130px">Masuk ke Akun Anda</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert ok"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert"><?= implode('<br>', session()->getFlashdata('errors')) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('masuk') ?>" method="post" class="form">
        <?= csrf_field() ?>
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email anda" value="<?= old('email') ?>">

        <label>Kata Sandi</label>
        <div class="pw">
            <input type="password" name="password" id="pw" placeholder="Masukkan kata sandi">
            <button type="button" onclick="togglePw()">&#128065;</button>
        </div>

        <button type="submit" class="btn btn-dark btn-small">Masuk</button>
    </form>
</div>
<script>
function togglePw(){const e=document.getElementById('pw');e.type=e.type==='password'?'text':'password';}
</script>
</body>
</html>