<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends Controller
{
    /**
     * Display platform metrics.
     * Only accessible by admin.
     *
     * @return JsonResponse|View
     */
    public function metrics(Request $request):JsonResponse|View
    {
        $totalJobs = Job::count();
        $totalUsers = User::count();
        $totalApplications = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();

        $metrics = [
            'total_jobs' => $totalJobs,
            'total_users' => $totalUsers,
            'total_applications' => $totalApplications,
            'pending_applications' => $pendingApplications,
        ];

        if ($request->expectsJson()) {
            return response()->json($metrics);
        }

        return view('admin.metrics', compact('metrics'));
    }
}
