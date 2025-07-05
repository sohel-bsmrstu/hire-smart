<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications for a specific job.
     * Only accessible by the employer who owns the job.
     *
     * @param  \App\Models\Job  $job
     * @return View
     */
    public function employerApplications(Job $job): View
    {
        $applications = $job->applications()->with('candidate')->latest()->get();

        return view('employer.applications.index', compact('job', 'applications'));
    }

    /**
     * Store a newly created application in storage.
     * Only accessible by candidates.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'job_id' => 'required|exists:job_posts,id',
            'cover_letter' => 'nullable|string',
        ]);

        // Check if candidate has already applied to this job
        $existingApplication = Application::where('job_id', $request->job_id)
                                ->where('user_id', Auth::id())
                                ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        Auth::user()->applications()->create([
            'job_id' => $request->job_id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
        ]);

        return redirect()->route('jobs.show', $request->job_id)->with('success', 'Application submitted successfully!');
    }

    /**
     * Update the status of a specific application.
     * Only accessible by the employer who owns the job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @return JsonResponse
     */
    public function updateStatus(Request $request, Application $application): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
        ]);

        if (Auth::user()->id !== $application->job->user_id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            abort(403, 'Unauthorized action.');
        }

        $application->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Application status updated successfully!',
                'application' => $application
            ]);
        }
    }
}
