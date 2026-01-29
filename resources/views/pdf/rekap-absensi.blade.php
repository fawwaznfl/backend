<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #f3f4f6; }
        .header { display: flex; align-items: center; }
        .logo { width: 70px; }
        .company { margin-left: 12px; }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path($company->logo ?? 'logo/company.png') }}" class="logo">
    <div class="company">
        <strong>{{ $company->name ?? 'Nama Company' }}</strong><br>
        {{ $company->alamat ?? '-' }}<br>
        {{ $company->email ?? '' }} | {{ $company->telp ?? '' }}
    </div>
</div>

<hr>

<p>
    <strong>Rekap Absensi Detail</strong><br>
    Periode: {{ $start }} s/d {{ $end }}
</p>

<table>
    <thead>
        <tr>
            <th>Nama Pegawai</th>
            <th>Shift</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Telat</th>
            <th>Jam Pulang</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $r)
        <tr>
            <td>{{ $r[0] }}</td>
            <td>{{ $r[1] }}</td>
            <td>{{ $r[2] }}</td>
            <td>{{ $r[3] }}</td>
            <td>{{ $r[4] }}</td>
            <td>{{ $r[6] }}</td>
            <td>{{ $r[9] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
