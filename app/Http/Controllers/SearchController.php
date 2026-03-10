<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        return view('search.index');
    }

    public function results(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');

        if (strlen($query) < 2) {
            return response()->json([
                'pasien' => [],
                'rekam_medis' => [],
                'peminjaman' => [],
                'pengguna' => [],
            ]);
        }

        $results = [
            'pasien' => [],
            'rekam_medis' => [],
            'peminjaman' => [],
            'pengguna' => [],
        ];

        if ($type === 'all' || $type === 'pasien') {
            $results['pasien'] = Pasien::where('nama_lengkap', 'like', "%{$query}%")
                ->orWhere('no_rekam_medis', 'like', "%{$query}%")
                ->orWhere('nik', 'like', "%{$query}%")
                ->limit(10)
                ->get();
        }

        if ($type === 'all' || $type === 'rekam_medis') {
            $results['rekam_medis'] = RekamMedis::where('no_rekam_medis', 'like', "%{$query}%")
                ->orWhere('kode_dokumen', 'like', "%{$query}%")
                ->limit(10)
                ->get();
        }

        if ($type === 'all' || $type === 'peminjaman') {
            $results['peminjaman'] = Peminjaman::where('no_peminjaman', 'like', "%{$query}%")
                ->orWhere('nama_peminjam_luar', 'like', "%{$query}%")
                ->orWhere('institusi_peminjam', 'like', "%{$query}%")
                ->limit(10)
                ->get();
        }

        if ($type === 'all' || $type === 'pengguna') {
            $results['pengguna'] = User::where('nama_lengkap', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->limit(10)
                ->get();
        }

        return response()->json($results);
    }
}
