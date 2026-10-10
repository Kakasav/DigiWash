<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;

class DashboardPlg extends BaseController
{
    public function index()
    {
        $data = [
            'nama'         => session()->get('nama'),
            'pesananAktif' => 1,                        // sementara dummy
            'statusCuci'   => 'Sedang Dicuci',          // sementara dummy
            'statusBayar'  => 'Menunggu Pembayaran',    // sementara dummy
        ];

        return view('pelanggan/dashboard_plg', $data);
    }
}