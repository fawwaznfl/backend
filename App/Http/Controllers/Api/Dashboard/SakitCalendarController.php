<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SakitCalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $start = Carbon::parse($request->start);
        $end   = Carbon::parse($request->end);

        $query = Cuti::with('pegawai')
            ->where('jenis_cuti', 'sakit')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
            });

        if ($user->dashboard_type === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        $cutis = $query->get();

        $events = [];

        foreach ($cutis as $cuti) {
            $startDate = Carbon::parse($cuti->tanggal_mulai);
            $endDate   = Carbon::parse($cuti->tanggal_selesai);

            while ($startDate->lte($endDate)) {
                $events[] = [
                    'title' => 'Sakit: ' . $cuti->pegawai->username,
                    'start' => $startDate->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => '#eab308', // kuning
                    'borderColor' => '#eab308',
                    'textColor' => '#ffffff',
                ];

                $startDate->addDay();
            }
        }

        return response()->json($events);
    }


    public function cuti(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end   = Carbon::parse($request->end);

        $query = Cuti::with('pegawai')
            ->where('jenis_cuti', 'cuti')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
            });

        // filter admin
        if ($request->user()->dashboard_type === 'admin') {
            $query->where('company_id', $request->user()->company_id);
        }

        $cutis = $query->get();

        $events = [];

        foreach ($cutis as $cuti) {
            $startDate = Carbon::parse($cuti->tanggal_mulai);
            $endDate   = Carbon::parse($cuti->tanggal_selesai);

            while ($startDate->lte($endDate)) {
                $events[] = [
                    'title' => 'Cuti: ' . $cuti->pegawai->username,
                    'start' => $startDate->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => '#3b82f6', // biru
                    'borderColor' => '#3b82f6',
                    'textColor' => '#ffffff',
                ];
                $startDate->addDay();
            }
        }

        return response()->json($events);
    }

    public function izin(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end   = Carbon::parse($request->end);

        $query = Cuti::with('pegawai')
            ->where('jenis_cuti', 'izin_masuk')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
            });

        // filter admin
        if ($request->user()->dashboard_type === 'admin') {
            $query->where('company_id', $request->user()->company_id);
        }

        $cutis = $query->get();

        $events = [];

        foreach ($cutis as $cuti) {
            $startDate = Carbon::parse($cuti->tanggal_mulai);
            $endDate   = Carbon::parse($cuti->tanggal_selesai);

            while ($startDate->lte($endDate)) {
                $events[] = [
                    'title' => 'Izin: ' . $cuti->pegawai->username,
                    'start' => $startDate->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => '#06B6D4', 
                    'borderColor' => '#06B6D4',
                    'textColor' => '#ffffff',
                ];
                $startDate->addDay();
            }
        }

        return response()->json($events);
    }

    public function telat(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end   = Carbon::parse($request->end);

        $query = Cuti::with('pegawai')
            ->where('jenis_cuti', 'izin_telat')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
            });

        // filter admin
        if ($request->user()->dashboard_type === 'admin') {
            $query->where('company_id', $request->user()->company_id);
        }

        $cutis = $query->get();

        $events = [];

        foreach ($cutis as $cuti) {
            $startDate = Carbon::parse($cuti->tanggal_mulai);
            $endDate   = Carbon::parse($cuti->tanggal_selesai);

            while ($startDate->lte($endDate)) {
                $events[] = [
                    'title' => 'Izin Telat: ' . $cuti->pegawai->username,
                    'start' => $startDate->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => '#22C55E', 
                    'borderColor' => '#22C55E',
                    'textColor' => '#ffffff',
                ];
                $startDate->addDay();
            }
        }

        return response()->json($events);
    }

    public function pulang(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end   = Carbon::parse($request->end);

        $query = Cuti::with('pegawai')
            ->where('jenis_cuti', 'izin_pulang_cepat')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_mulai', [$start, $end])
                ->orWhereBetween('tanggal_selesai', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('tanggal_mulai', '<=', $start)
                        ->where('tanggal_selesai', '>=', $end);
                });
            });

        // filter admin
        if ($request->user()->dashboard_type === 'admin') {
            $query->where('company_id', $request->user()->company_id);
        }

        $cutis = $query->get();

        $events = [];

        foreach ($cutis as $cuti) {
            $startDate = Carbon::parse($cuti->tanggal_mulai);
            $endDate   = Carbon::parse($cuti->tanggal_selesai);

            while ($startDate->lte($endDate)) {
                $events[] = [
                    'title' => 'Izin Pulang Cepat: ' . $cuti->pegawai->username,
                    'start' => $startDate->toDateString(),
                    'allDay' => true,
                    'backgroundColor' => '#FFCA28', 
                    'borderColor' => '#FFCA28',
                    'textColor' => '#ffffff',
                ];
                $startDate->addDay();
            }
        }

        return response()->json($events);
    }
    
}
