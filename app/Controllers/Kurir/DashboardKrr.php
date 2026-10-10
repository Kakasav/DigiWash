<?php

namespace App\Controllers\Kurir;

use App\Controllers\BaseController;

class DashboardKrr extends BaseController
{
    public function index()
    {
        $kurir = \Config\Database::connect()->table('kurir')
            ->select('nama, status')
            ->where('id_kurir', (int) session()->get('user_id'))
            ->get()->getRowArray() ?? [];

        return view('kurir/dashboard_krr', [
            'nama'   => (string) ($kurir['nama'] ?? session()->get('nama')),
            'status' => (string) ($kurir['status'] ?? 'tersedia'),
            'aktif'  => 'beranda',
        ]);
    }
}