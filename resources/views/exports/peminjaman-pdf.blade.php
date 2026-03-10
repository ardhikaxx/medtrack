<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #1a6f8a; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status { padding: 3px 8px; border-radius: 4px; font-size: 10px; }
        .status-menunggu { background: #fef3c7; color: #92400e; }
        .status-disetujui { background: #dbeafe; color: #1e40af; }
        .status-dipinjam { background: #ede9fe; color: #6d28d9; }
        .status-selesai { background: #d1fae5; color: #065f46; }
        .status-terlambat { background: #fee2e2; color: #991b1b; }
        .status-ditolak { background: #f3f4f6; color: #374151; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Peminjaman Dokumen Rekam Medis</h2>
        <p>Klinik Pratama Rawat Inap Husada</p>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
        @if($filter['startDate'] || $filter['endDate'])
        <p>Periode: {{ $filter['startDate'] ?? '-' }} s/d {{ $filter['endDate'] ?? '-' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Peminjaman</th>
                <th>Peminjam</th>
                <th>Jml</th>
                <th>Tujuan</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
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
                <td>{{ $pm->tujuan_peminjaman }}</td>
                <td>{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $pm->tanggal_kembali->format('d/m/Y') }}</td>
                <td>
                    @php
                        $statusClass = match($pm->status_peminjaman) {
                            'menunggu' => 'status-menunggu',
                            'disetujui' => 'status-disetujui',
                            'dipinjam' => 'status-dipinjam',
                            'selesai' => 'status-selesai',
                            'terlambat' => 'status-terlambat',
                            'ditolak' => 'status-ditolak',
                            default => ''
                        };
                        $statusLabel = match($pm->status_peminjaman) {
                            'menunggu' => 'Menunggu',
                            'disetujui' => 'Disetujui',
                            'dipinjam' => 'Dipinjam',
                            'selesai' => 'Selesai',
                            'terlambat' => 'Terlambat',
                            'ditolak' => 'Ditolak',
                            default => $pm->status_peminjaman
                        };
                    @endphp
                    <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Records: {{ $peminjaman->count() }}</p>
    </div>
</body>
</html>
