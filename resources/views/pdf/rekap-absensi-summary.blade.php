<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 70px;
        }

        .company {
            margin-left: 12px;
            font-size: 10px;
        }

        hr {
            margin: 8px 0 12px;
        }

        /* ===== TITLE ===== */
        .title {
            text-align: center;
            margin-bottom: 12px;
        }

        .title strong {
            font-size: 13px;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
            font-size: 9px;
        }

        td.name {
            text-align: left;
            white-space: nowrap;
        }

        .multiline {
            white-space: pre-line;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>

{{-- ================= HEADER (DETAIL STYLE) ================= --}}
<div class="header">
    <img
        src="{{ public_path($company->logo ?? 'logo/company.png') }}"
        class="logo"
    >
    <div class="company">
        <strong>{{ $company->name ?? 'Nama Company' }}</strong><br>
        {{ $company->alamat ?? '-' }}<br>
        {{ $company->email ?? '' }} | {{ $company->telp ?? '' }}
    </div>
</div>

<hr>

<div class="title">
    <strong>Rekap Absensi</strong><br>
    Periode: {{ $start }} s/d {{ $end }}
</div>

{{-- ================= TABLE (SUMMARY – TETAP) ================= --}}
<table>
    <thead>
        <tr>
            <th>NAMA PEGAWAI</th>
            <th>CUTI</th>
            <th>IZIN<br>MASUK</th>
            <th>IZIN<br>TELAT</th>
            <th>IZIN PULANG<br>CEPAT</th>
            <th>HADIR</th>
            <th>ALFA</th>
            <th>LIBUR</th>
            <th>TOTAL<br>TELAT</th>
            <th>TOTAL PULANG<br>CEPAT</th>
            <th>TOTAL<br>LEMBUR</th>
            <th>PERSENTASE<br>KEHADIRAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <td class="name">{{ $row->nama_pegawai }}</td>
                <td>{{ $row->total_cuti }} x</td>
                <td>{{ $row->total_izin_masuk }} x</td>
                <td>{{ $row->total_izin_telat }} x</td>
                <td>{{ $row->total_izin_pulang_cepat }} x</td>
                <td>{{ $row->total_hadir }} x</td>
                <td>{{ $row->total_alfa }} x</td>
                <td>{{ $row->total_libur }} x</td>

                <td class="multiline">
                    {{ $row->total_telat }}
                </td>

                <td class="multiline">
                    {{ $row->total_pulang_cepat }}
                </td>

                <td class="multiline">
                    {{ $row->total_lembur }}
                </td>

                <td class="nowrap">
                    {{ $row->persentase_kehadiran }} %
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
