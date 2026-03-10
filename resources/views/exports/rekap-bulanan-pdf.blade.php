<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Bulanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        .stats { display: flex; justify-content: space-around; margin: 20px 0; }
        .stat-box { text-align: center; padding: 15px; background: #f9f9f9; border-radius: 8px; min-width: 120px; }
        .stat-box .number { font-size: 24px; font-weight: bold; color: #1a6f8a; }
        .stat-box .label { font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #8b5cf6; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Rekap Bulanan Peminjaman & Pengembalian</h2>
        <p>Klinik Pratama Rawat Inap Husada</p>
        <p>Bulan: {{\App\Models\Peminjaman::getBulanIndo($bulan)}} {{ $tahun }}</p>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="number">{{ $stats['total_peminjaman'] }}</div>
            <div class="label">Total Peminjaman</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['total_pengembalian'] }}</div>
            <div class="label">Total Pengembalian</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['peminjaman_selesai'] }}</div>
            <div class="label">Selesai</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['peminjaman_aktif'] }}</div>
            <div class="label">Aktif</div>
        </div>
        <div class="stat-box">
            <div class="number" style="color: #ef4444;">{{ $stats['terlambat'] }}</div>
            <div class="label">Terlambat</div>
        </div>
    </div>

    <h3>Detail Peminjaman</h3>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Peminjaman</th>
                <th>Peminjam</th>
                <th>Jml</th>
                <th>Tgl Pinjam</th>
                <th>Batas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $index => $pm)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pm->no_peminjaman }}</td>
                <td>{{ $pm->peminjam->nama_lengkap ?? '-' }}</td>
                <td>{{ $pm->rekamMedis->count() }}</td>
                <td>{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $pm->tanggal_kembali->format('d/m/Y') }}</td>
                <td>{{ ucfirst($pm->status_peminjaman) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Peminjaman: {{ $peminjaman->count() }} | Total Pengembalian: {{ $pengembalian->count() }}</p>
    </div>
</body>
</html>
