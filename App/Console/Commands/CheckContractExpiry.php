<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kontrak;
use App\Scope\CompanyScope;
use Carbon\Carbon;
use App\Services\NotificationService;

class CheckContractExpiry extends Command
{
    protected $signature = 'contract:check-expiry';
    protected $description = 'Check kontrak yang hampir habis dan kirim notifikasi';

    public function handle()
    {
        $today = Carbon::today();

        $kontraks = Kontrak::withoutGlobalScope(CompanyScope::class)
            ->whereNotNull('tanggal_selesai')
            ->get();

        

        foreach ($kontraks as $kontrak) {
            $endDate = Carbon::parse($kontrak->tanggal_selesai);
            $daysLeft = $today->diffInDays($endDate, false);
            $this->info("Kontrak {$kontrak->id} → daysLeft={$daysLeft}");

            if ($daysLeft < 0) {
                continue;
            }


            // H-30
            if ($daysLeft <= 30 && $daysLeft > 29 && !$kontrak->notified_h30) {
                $this->notify($kontrak, 30);
                $kontrak->update(['notified_h30' => true]);
            }

            // H-7
            if ($daysLeft <= 7 && $daysLeft > 6 && !$kontrak->notified_h7) {
                $this->notify($kontrak, 7);
                $kontrak->update(['notified_h7' => true]);
            }
        }

        $this->info('Check contract expiry selesai.');

    }

    /*** Kirim notifikasi kontrak hampir habis */
    private function notify($kontrak, $days)
    {
        // Notifikasi ke Admin & Superadmin
        NotificationService::create(
            [
                'type' => 'contract_expiring',
                'title' => "Kontrak H-{$days}",
                'message' => "Kontrak akan habis {$days} hari lagi",
                'company_id' => $kontrak->company_id,
                'data' => [
                    'kontrak_id' => $kontrak->id,
                    'pegawai_id' => $kontrak->pegawai_id,
                    'days_left' => $days,
                ]
            ],
            [
                [
                    'role' => 'admin',
                    'company_id' => $kontrak->company_id,
                ],
                [
                    'role' => 'superadmin',
                ],
            ]
        );

        // ✅ Notifikasi ke Pegawai yang bersangkutan
        NotificationService::create(
            [
                'type' => 'contract_expiring_personal',
                'title' => "Kontrak Anda H-{$days}",
                'message' => "Kontrak Anda akan berakhir dalam {$days} hari lagi. Silakan hubungi HRD untuk perpanjangan.",
                'company_id' => $kontrak->company_id,
                'data' => [
                    'kontrak_id' => $kontrak->id,
                    'days_left' => $days,
                    'tanggal_selesai' => $kontrak->tanggal_selesai,
                ]
            ],
            [
                [
                    'user_id' => $kontrak->pegawai_id,
                ],
            ]
        );
    }
}
