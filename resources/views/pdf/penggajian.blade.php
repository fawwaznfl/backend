<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2, h3 {
            margin: 0;
            padding: 0;
        }

        .center { text-align: center; }
        .right { text-align: right; }

        .header p {
            margin: 2px 0;
            font-size: 10px;
        }

        .line {
            border-top: 1px solid #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 6px;
            border: 1px solid #000;
        }

        .no-border td {
            border: none;
            padding: 3px 2px;
        }

        .label {
            width: 35%;
        }

        .total-box {
            margin-top: 12px;
            border: 1px solid #000;
            padding: 10px;
            font-size: 13px;
            font-weight: bold;
        }
        
        .no-box-table td,
        .no-box-table th {
            border: none;
            padding: 4px 2px;
        }

        .no-box-table th {
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        .subtotal-line {
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        .total-text {
            font-size: 13px;
            font-weight: bold;
        }

        .section-title {
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 6px;
        }

        .pajak-table {
            width: 100%;
        }

        .pajak-table th,
        .pajak-table td {
            border: none;
            padding: 6px 2px;
        }

        .pajak-table thead th {
            font-weight: bold;
        }

        .pajak-table .right {
            text-align: right;
        }

        .pajak-table .center {
            text-align: center;
        }

        .sub-text {
            font-size: 12px;
            font-weight: normal;
        }

        .sub-text-bold {
            font-size: 11px;      
            font-weight: bold;    
        }
        .subsub-text-bold {
            font-size: 12px;      
            font-weight: bold;    
        }
        .summary-text {
            font-size: 11px;
            font-weight: bold;
            padding-top: 6px;
        }
        .summary-table td {
            padding: 3px 2px;
            font-size: 11px;
        }

        .summary-table tr {
            line-height: 1.3;
        }

    </style>
</head>
<body>

<!-- ================= HEADER ================= -->
<div class="header center">
    <h2>{{ $data->company->name ?? 'NAMA PERUSAHAAN' }}</h2>
    <p>{{ $data->company->alamat ?? '-' }}</p>
    <p>
        {{ $data->company->email ?? '' }}
        @if(!empty($data->company->telp))
            | {{ $data->company->telp }}
        @endif
    </p>
</div>

<div class="line"></div>

<h3 class="center">Bukti Pemotongan Pajak (PPH 21)</h3>

<div class="line"></div>

<!-- ================= DATA PEGAWAI ================= -->
<table class="no-border">
    <tr>
        <td width="50%" valign="top">
            <table class="no-border">
                <tr>
                    <td class="label">Nama Pegawai</td>
                    <td>: {{ $data->pegawai->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td>: {{ $data->pegawai->divisi->nama ?? '-' }}</td>
                </tr>
            </table>
        </td>

        <td width="50%" valign="top">
            <table class="no-border">
                <tr>
                    <td class="label">Status Pajak</td>
                    <td>: {{ $data->pegawai->status_pajak ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Cetak</td>
                    <td>: {{ now()->format('d F Y') }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<!-- ================= RINCIAN PEMOTONGAN PAJAK ================= -->
<div class="section-title">
    RINCIAN PEMOTONGAN PAJAK
</div>

<table class="pajak-table">
    <thead>
        <tr>
            <th align="left">
                Komponen<br>
                <span class="sub-text">(Penghasilan Kena Pajak)</span>
            </th>
            <th class="right">Jumlah (Rp)</th>
            <th class="center">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Gaji Pokok</td>
            <td class="right">
                {{ number_format($data->gaji_pokok ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Uang Transport</td>
            <td class="right">
                {{ number_format($data->uang_transport ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        @php
            $totalLembur = ($data->lembur ?? 0) * ($data->uang_lembur ?? 0);
        @endphp
        <tr>
            <td>Lembur</td>
            <td class="right">
                {{ number_format($totalLembur, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        @php
            $totalBonusKehadiran = ($data->kehadiran_100 ?? 0) * ($data->bonus_kehadiran ?? 0);
        @endphp
        <tr>
            <td>Bonus Kehadiran</td>
            <td class="right">
                {{ number_format($totalBonusKehadiran, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        @php
            $totalThr = ($data->thr ?? 0) * ($data->tunjangan_hari_raya ?? 0);
        @endphp
        <tr>
            <td>THR</td>
            <td class="right">
                {{ number_format($totalThr, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Bonus Pribadi</td>
            <td class="right">
                {{ number_format($data->bonus_pribadi ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Bonus Team</td>
            <td class="right">
                {{ number_format($data->bonus_team ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Bonus Jackpot</td>
            <td class="right">
                {{ number_format($data->bonus_jackpot ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Tunjangan BPJS Kesehatan</td>
            <td class="right">
                {{ number_format($data->tunjangan_bpjs_kesehatan ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Tunjangan BPJS Ketenagakerjaan</td>
            <td class="right">
                {{ number_format($data->tunjangan_bpjs_ketenagakerjaan ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Tunjangan Pajak</td>
            <td class="right">
                {{ number_format($data->tunjangan_pajak ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <div class="sub-text-bold">
            (Penghasilan Tidak Kena Pajak)
        </div>

        <tr>
            <td>Reimbursement</td>
            <td class="right">
                {{ number_format($data->reimbursement ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">TIDAK</td>
        </tr>

        <div class="sub-text-bold">
            (Pengurangan Pajak)
        </div>

        <tr>
            <td>Potongan BPJS Kesehatan</td>
            <td class="right">
                {{ number_format($data->potongan_bpjs_kesehatan ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td>Potongan BPJS Ketenagakerjaan</td>
            <td class="right">
                {{ number_format($data->potongan_bpjs_ketenagakerjaan ?? 0, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        @php
            $totalTerlambat = ($data->terlambat ?? 0) * ($data->uang_terlambat ?? 0);
        @endphp
        <tr>
            <td>Potongan Terlambat</td>
            <td class="right">
                {{ number_format($totalTerlambat, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        @php
            $totalMangkir = ($data->mangkir ?? 0) * ($data->uang_mangkir ?? 0);
        @endphp
        <tr>
            <td>Potongan Mangkir</td>
            <td class="right">
                {{ number_format($totalMangkir, 0, ',', '.') }}
            </td>
            <td class="center">YA</td>
        </tr>

        <tr>
            <td colspan="4">
                <div style="border-top:1px solid #000; margin:6px 0;"></div>
            </td>
        </tr>

        @php
            $totalBruto =
                ($data->gaji_pokok ?? 0) +
                ($data->uang_transport ?? 0) +
                ($totalLembur ?? 0) +
                ($totalBonusKehadiran ?? 0) +
                ($totalThr ?? 0) +
                ($data->bonus_pribadi ?? 0) +
                ($data->bonus_team ?? 0) + 
                ($data->bonus_jackpot ?? 0) +
                ($data->tunjangan_bpjs_kesehatan ?? 0) + 
                ($data->tunjangan_bpjs_ketenagakerjaan ?? 0) + 
                ($data->tunjangan_pajak ?? 0);
        @endphp

        @php
            $totalPengurangan =
                ($data->potongan_bpjs_kesehatan ?? 0) +
                ($data->potongan_bpjs_ketenagakerjaan ?? 0) +
                ($totalTerlambat ?? 0) +
                ($totalMangkir ?? 0);
        @endphp

        @php
            $penghasilanNettoSebulan = $totalBruto - $totalPengurangan ;
        @endphp

        @php
            $penghasilanNettoSetahun = $penghasilanNettoSebulan * 12  ;
        @endphp

        <tr>
        <td colspan="3" class="summary-text">
            <table width="100%" class="no-border summary-table">
                <tr>
                    <td>Total Bruto (Kena Pajak)</td>
                    <td class="right">
                        Rp {{ number_format($totalBruto, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Total Pengurangan</td>
                    <td class="right">
                        Rp {{ number_format($totalPengurangan, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td><strong>Penghasilan Netto Sebulan</strong></td>
                    <td class="right">
                        <strong>Rp {{ number_format($penghasilanNettoSebulan, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Penghasilan Netto Setahun</strong></td>
                    <td class="right">
                        <strong>Rp {{ number_format($penghasilanNettoSetahun, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
            <td colspan="4">
                <div style="border-top:1px solid #000; margin:6px 0;"></div>
            </td>
    </tr>

    <tr>
        <td colspan="4" style="font-size:10px; font-style:italic; text-align:right;">
            Dicetak pada: {{ now()->format('d F Y, H:i') }}
        </td>
    </tr>




    </tbody>
</table>

<br>

<p style="font-size:10px;">
    * Slip gaji ini dihasilkan secara otomatis oleh sistem dan tidak memerlukan tanda tangan.
</p>

</body>
</html>
