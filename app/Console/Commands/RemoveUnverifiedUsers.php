<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RemoveUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remove-unverified';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes unverified users older than a certain period.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // For simplicity, defining "unverified" as users without email_verified_at
        // and created more than 7 days ago.
        $thresholdDate = Carbon::now()->subDays(7);

        $removedUsersCount = User::whereNull('email_verified_at')
                                 ->where('created_at', '<', $thresholdDate)
                                 ->delete();

        Log::info("Removed {$removedUsersCount} unverified users.");
        $this->info("Removed {$removedUsersCount} unverified users.");
    }
}
