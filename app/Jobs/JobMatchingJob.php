<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Job;
use App\Models\User;

class JobMatchingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('JobMatchingJob started...');

        $jobs = Job::all();
        $candidates = User::where('role', 'candidate')->get();

        foreach ($jobs as $job) {
            foreach ($candidates as $candidate) {
                // Basic matching: if job location contains part of candidate's name (mocked for simplicity)
                // In reality, compare skills, location, salary ranges etc.
                if (str_contains(strtolower($job->location), strtolower(substr($candidate->name, 0, 3)))) {
                    Log::info("Match found: Candidate '{$candidate->name}' for Job '{$job->title}'");
                    // Mock a notification (e.g., send email, push notification, or save to a notifications table)
                    Log::info("Notification queued for candidate {$candidate->email} about job {$job->title}");
                }
            }
        }

        Log::info('JobMatchingJob finished.');
    }
}
