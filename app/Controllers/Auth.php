<?php

namespace App\Controllers;

class Auth extends BaseController
{
    /** Halaman tujuan setelah login, menurut peran. */
    private function tujuan(string $peran): string
    {
        return match ($peran) {
            'admin' => '/admin/dashboard',
            'kurir' => '/kurir/dashboard',
            default => '/dashboard',
        };
    }

    /** Kalau sudah login, langsung arahkan ke dashboard-nya. */
    private function sudahMasuk()
    {
        if (session()->get('logged_in')) {
            return redirect()->to($this->tujuan((string) session()->get('role')));
        }

        return null;
    }

    /** Email sudah dipakai di tabel mana pun? */
    private function emailDipakai(string $email): bool
    {
        $db = \Config\Database::connect();

        foreach (['admin', 'kurir', 'pelanggan'] as $tabel) {
            if ($db->table($tabel)->where('email', $email)->countAllResults() > 0) {
                return true;
            }
        }

        return false;
    }

    // ---------- Daftar (khusus pelanggan) ----------

    public function daftar()
    {
        if ($r = $this->sudahMasuk()) {
            return $r;
        }

        return view('daftar_akun');
    }

    public function prosesDaftar()
    {
        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'telp'     => 'required|numeric|min_length[10]|max_length[20]|is_unique[pelanggan.no_hp]',
            'email'    => 'required|valid_email|max_length[100]',
            'password' => 'required|min_length[6]',
            'setuju'   => 'required',
        ];

        $pesan = [
            'telp'   => ['is_unique' => 'Nomor telepon sudah terdaftar.'],
            'setuju' => ['required' => 'Setujui ketentuan layanan dan kebijakan privasi dulu.'],
        ];

        if (! $this->validate($rules, $pesan)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = trim((string) $this->request->getPost('email'));

        if ($this->emailDipakai($email)) {
            return redirect()->back()->withInput()->with('errors', ['Email sudah terdaftar.']);
        }

        \Config\Database::connect()->table('pelanggan')->insert([
            'nama'     => trim((string) $this->request->getPost('nama')),
            'no_hp'    => trim((string) $this->request->getPost('telp')),
            'alamat'   => '', // dilengkapi nanti lewat menu Alamat
            'email'    => $email,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/masuk')->with('success', 'Akun berhasil dibuat, silakan masuk.');
    }

    // ---------- Masuk (admin, kurir, pelanggan) ----------

    public function masuk()
    {
        if ($r = $this->sudahMasuk()) {
            return $r;
        }

        return view('masuk_akun');
    }

    public function prosesMasuk()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = trim((string) $this->request->getPost('email'));
        $sandi = (string) $this->request->getPost('password');
        $db    = \Config\Database::connect();

        // Nama tabel sama dengan nama peran; kolom kuncinya berbeda-beda.
        $kunci = [
            'admin'     => 'id_admin',
            'kurir'     => 'id_kurir',
            'pelanggan' => 'id_pelanggan',
        ];

        foreach ($kunci as $peran => $kolomId) {
            $akun = $db->table($peran)->where('email', $email)->get()->getRowArray();

            if (! $akun || ! password_verify($sandi, (string) $akun['password'])) {
                continue;
            }

            if (($akun['status'] ?? 'aktif') === 'nonaktif') {
                return redirect()->back()->withInput()
                    ->with('errors', ['Akun dinonaktifkan. Hubungi admin.']);
            }

            session()->regenerate();
            session()->remove('foto');
            session()->set([
                'logged_in' => true,
                'role'      => $peran,
                'peran'     => $peran,
                'user_id'   => (int) $akun[$kolomId],
                'nama'      => (string) $akun['nama'],
                'email'     => (string) $akun['email'],
            ]);

            return redirect()->to($this->tujuan($peran));
        }

        return redirect()->back()->withInput()->with('errors', ['Email atau kata sandi salah.']);
    }

    // ---------- Keluar ----------

    public function keluar()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}