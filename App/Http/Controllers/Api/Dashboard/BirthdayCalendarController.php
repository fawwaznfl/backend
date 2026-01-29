<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BirthdayCalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $year = $request->year ?? now()->year;

        $pegawaiQuery = Pegawai::whereNotNull('tgl_lahir')
            ->where('status', 'active');

        if ($user->dashboard_type === 'admin') {
            $pegawaiQuery->where('company_id', $user->company_id);
        }

        $events = $pegawaiQuery->get()->map(function ($pegawai) use ($year) {
            $birthDate = Carbon::parse($pegawai->tgl_lahir);

            return [
                'title' => '🎉 Ulang Tahun ' . $pegawai->username,
                'start' => Carbon::create(
                    $year,
                    $birthDate->month,
                    $birthDate->day
                )->toDateString(),
                'allDay' => true,
                'backgroundColor' => '#ec4899', // pink-500
                'borderColor' => '#ec4899',
                'textColor' => '#ffffff',
            ];
        });

        return response()->json($events);
    }
}
