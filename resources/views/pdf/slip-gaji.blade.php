<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2, h3 {
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

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

        td {
            padding: 4px 2px;
            vertical-align: top;
        }

        .label {
            width: 35%;
        }

        .right {
            text-align: right;
        }

        .section-title {
            font-weight: bold;
            background: #f2f2f2;
            padding: 5px;
            margin-top: 8px;
        }

        .total-box {
            border: 1px solid #000;
            padding: 8px;
            margin-top: 10px;
            font-size: 13px;
            font-weight: bold;
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header center">
    <h2>{{ $payroll->company->name }}</h2>

    <p>{{ $payroll->company->alamat }}</p>

    <p class="small">
        {{ $payroll->company->email }}
        @if($payroll->company->telp)
            | {{ $payroll->company->telp }}
        @endif
    </p>
</div>


<div class="line"></div>



<h3 class="center">SLIP GAJI PEGAWAI</h3>

<div class="line"></div>

<!-- DATA PEGAWAI -->
<table width="100%">
    <tr>
        <!-- KIRI -->
        <td width="50%" valign="top">
            <table width="100%">
                <tr>
                    <td class="label">Nama Pegawai</td>
                    <td>: {{ $payroll->pegawai->name }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td>: {{ $payroll->pegawai->divisi->nama }}</td>
                </tr>
                <tr>
                    <td class="label">No. Rekening</td>
                    <td>: {{ $payroll->pegawai->rekening }}</td>
                </tr>
            </table>
        </td>

        <!-- KANAN -->
        <td width="50%" valign="top">
            <table width="100%">
                <tr>
                    <td class="label">Bulan / Tahun</td>
                    <td>: {{ $payroll->bulan }} {{ $payroll->tahun }}</td>
                </tr>
                <tr>
                    <td class="label">Periode Gaji</td>
                    <td>: {{ $payroll->periode }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<!-- RINCIAN GAJI -->
<div class="section-title">RINCIAN GAJI BULAN INI</div>

<table width="100%">
    <tr>
        <td>Gaji Pokok</td>
        <td width="10" class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->gaji_pokok,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Uang Makan & Transport</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->uang_makan,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Lembur</td>
        <td class="center">:</td>
        <td>{{ $payroll->lembur }} Jam</td>
        <td class="right">
            Rp {{ number_format($payroll->uang_lembur,0,',','.') }}
        </td>
    </tr>


    <tr>
        <td>Bonus 100% Kehadiran</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->bonus_kehadiran,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Bonus Pribadi</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->bonus_pribadi,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Bonus Team</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->bonus_team,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Bonus Jackpot</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->bonus_jackpot,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Tunjangan Hari Raya</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->tunjangan_hari_raya,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Reimbursement</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->reimbursement,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Tunjangan BPJS Kesehatan</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->tunjangan_bpjs_kesehatan,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Tunjangan BPJS Ketenagakerjaan</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->tunjangan_bpjs_ketenagakerjaan,0,',','.') }}</td>
    </tr>

    <tr>
        <td>Tunjangan Pajak</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">Rp {{ number_format($payroll->tunjangan_pajak,0,',','.') }}</td>
    </tr>

    <!-- GARIS -->
    <tr>
        <td colspan="4">
            <div style="border-top:1px solid #000; margin:6px 0;"></div>
        </td>
    </tr>

    <!-- SUBTOTAL -->
    <tr>
        <td><strong>Subtotal</strong></td>
        <td class="center"><strong>:</strong></td>
        <td></td>
        <td class="right">
            <strong>Rp {{ number_format($payroll->total_tambah,0,',','.') }}</strong>
        </td>
    </tr>
</table>


<!-- POTONGAN -->
<div class="section-title">DIKURANGI</div>

<table width="100%">
    <tr>
        <td>Keterlambatan</td>
        <td width="10" class="center">:</td>
        <td>{{ $payroll->terlambat }} Kali</td>
        <td class="right">
            Rp {{ number_format($payroll->uang_terlambat,0,',','.') }}
        </td>
    </tr>

    <tr>
        <td>Mangkir</td>
        <td class="center">:</td>
        <td>{{ $payroll->mangkir }} Hari</td>
        <td class="right">
            Rp {{ number_format($payroll->uang_mangkir,0,',','.') }}
        </td>
    </tr>

    <tr>
        <td>Izin</td>
        <td class="center">:</td>
        <td>{{ $payroll->izin }} Kali</td>
        <td class="right">
            Rp {{ number_format($payroll->uang_izin,0,',','.') }}
        </td>
    </tr>

    <tr>
        <td>Kasbon</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">
            Rp {{ number_format($payroll->bayar_kasbon,0,',','.') }}
        </td>
    </tr>

    <tr>
        <td>Loss</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">
            Rp {{ number_format($payroll->loss,0,',','.') }}
        </td>
    </tr>

    <tr>
        <td>Potongan BPJS Kesehatan</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">
            Rp {{ number_format($payroll->potongan_bpjs_kesehatan,0,',','.') }}
        </td>
    </tr>

    <tr>
        <td>Potongan BPJS Ketenagakerjaan</td>
        <td class="center">:</td>
        <td></td>
        <td class="right">
            Rp {{ number_format($payroll->potongan_bpjs_ketenagakerjaan,0,',','.') }}
        </td>
    </tr>

    <!-- GARIS -->
    <tr>
        <td colspan="4">
            <div style="border-top:1px solid #000; margin:6px 0;"></div>
        </td>
    </tr>

    <!-- SUBTOTAL POTONGAN -->
    <tr>
        <td><strong>Subtotal</strong></td>
        <td class="center"><strong>:</strong></td>
        <td></td>
        <td class="right">
            <strong>
                Rp {{ number_format($payroll->total_pengurangan,0,',','.') }}
            </strong>
        </td>
    </tr>
</table>


<!-- TOTAL -->
<div class="total-box center">
    GAJI YANG DITERIMA <br>
    Rp {{ number_format($payroll->gaji_diterima,0,',','.') }}
</div>

</body>
</html>
