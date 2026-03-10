<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengembalian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #10b981; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Pengembalian Dokumen Rekam Medis</h2>
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
                <th>No. Pengembalian</th>
                <th>No. Peminjaman</th>
                <th>Peminjam</th>
                <th>Tgl Kembali</th>
                <th>Petugas</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengembalian as $index => $pg)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pg->no_pengembalian }}</td>
                <td>{{ $pg->peminjaman->no_peminjaman ?? '-' }}</td>
                <td>{{ $pg->peminjaman->peminjam->nama_lengkap ?? '-' }}</td>
                <td>{{ $pg->tanggal_pengembalian->format('d/m/Y') }}</td>
                <td>{{ $pg->petugas->nama_lengkap ?? '-' }}</td>
                <td>{{ $pg->kondisi_dokumen ?? 'Baik' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Records: {{ $pengembalian->count() }}</p>
    </div>
</body>
</html>
