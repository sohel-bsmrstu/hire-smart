<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ArchiveOldJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:archive-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archives job posts older than 30 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thresholdDate = Carbon::now()->subDays(30);
        $archivedJobsCount = Job::where('created_at', '<', $thresholdDate)
                                ->delete();

        Log::info("Archived {$archivedJobsCount} old job posts.");
        $this->info("Archived {$archivedJobsCount} old job posts.");
    }
}
