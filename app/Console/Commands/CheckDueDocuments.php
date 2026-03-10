<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Console\Command;

class CheckDueDocuments extends Command
{
    protected $signature = 'app:check-due-documents';
    protected $description = 'Check and send notifications for due/overdue documents';

    public function handle()
    {
        $this->info('Checking for documents...');
        
        $this->checkOverdueDocuments();
        $this->checkAlmostDueDocuments();
        
        $this->info('Done!');
    }

    protected function checkOverdueDocuments()
    {
        $today = now()->toDateString();
        
        $overduePeminjaman = Peminjaman::whereIn('status_peminjaman', ['dipinjam', 'terlambat'])
            ->where('tanggal_kembali_rencana', '<', $today)
            ->with('peminjam')
            ->get();

        foreach ($overduePeminjaman as $pm) {
            $existingNotification = Notification::where('user_id', $pm->peminjam_id)
                ->where('type', 'terlambat')
                ->where('notifiable_id', $pm->id)
                ->whereDate('created_at', today())
                ->exists();

            if (!$existingNotification) {
                Notification::createNotification(
                    $pm->peminjam_id,
                    'terlambat',
                    'Dokumen Terlambat!',
                    "Peminjaman {$pm->no_peminjaman} telah melewati batas waktu pengembalian. Segera kembalikan dokumen.",
                    $pm
                );
                $this->info("Created overdue notification for {$pm->no_peminjaman}");
            }
        }
    }

    protected function checkAlmostDueDocuments()
    {
        $threeDaysFromNow = now()->addDays(3)->toDateString();
        $today = now()->toDateString();

        $almostDuePeminjaman = Peminjaman::where('status_peminjaman', 'dipinjam')
            ->whereBetween('tanggal_kembali_rencana', [$today, $threeDaysFromNow])
            ->with('peminjam')
            ->get();

        foreach ($almostDuePeminjaman as $pm) {
            $daysLeft = now()->diffInDays($pm->tanggal_kembali_rencana);
            
            $existingNotification = Notification::where('user_id', $pm->peminjam_id)
                ->where('type', 'hampir_telat')
                ->where('notifiable_id', $pm->id)
                ->whereDate('created_at', today())
                ->exists();

            if (!$existingNotification) {
                Notification::createNotification(
                    $pm->peminjam_id,
                    'hampir_telat',
                    'Pengingat: Dokumen Akan Jatuh Tempo',
                    "Peminjaman {$pm->no_peminjaman} akan jatuh tempo dalam {$daysLeft} hari ({$pm->tanggal_kembali_rencana->format('d/m/Y')}). Segala kembalikan.",
                    $pm
                );
                $this->info("Created almost due notification for {$pm->no_peminjaman}");
            }
        }
    }
}
