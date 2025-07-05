<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPostRequest;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;// For caching example

class JobController extends Controller
{

     /**
     * Display a listing of the jobs.
     * Accessible by all (candidates, employers, guests).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        // Cache recent job listings for 5 minutes
        $jobs = Cache::remember('recent_jobs', 300, function () use ($request) {
            $query = Job::query();

            // Filtering options
            if ($request->has('keyword')) {
                $query->where(function($q) use ($request) {
                   $q->where(DB::raw('LOWER(title)'), 'LIKE', '%' . strtolower($request->keyword) . '%')
                      ->orWhere(DB::raw('LOWER(description)'), 'LIKE', '%' . strtolower($request->keyword) . '%');
                });
            }

            if ($request->has('location')) {
                $query->where(DB::raw('LOWER(location)'), 'LIKE', '%' . strtolower($request->location) . '%');
            }

            if ($request->has('skills')) {
                $query->where(DB::raw('LOWER(required_skills)'), 'LIKE', '%' . strtolower($request->skills) . '%');
            }

            return $query->latest()->get();
        });

        if ($request->expectsJson()) {
            return response()->json($jobs);
        }

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Display a listing of jobs posted by the authenticated employer.
     * Only accessible by employers.
     *
     * @return View
     */
    public function employerJobs(): View
    {
        $jobs = Auth::user()->jobs()->withCount('applications')->latest()->get();

        return view('employer.jobs.index', compact('jobs'));
    }

     /**
     * Show the form for creating a new job.
     * Only accessible by employers.
     *
     * @return View
     */
    public function create(): View
    {
        return view('employer.jobs.create');
    }

    /**
     * Show the form for editing the specified job.
     * Only accessible by the employer who posted the job.
     *
     * @param  \App\Models\Job  $job
     * @return View
     */
    public function edit(Job $job): View
    {
        return view('employer.jobs.edit', compact('job'));
    }

     /**
     * Display the specified job.
     * Accessible by all.
     *
     * @param  \App\Models\Job  $job
     * @return View|JsonResponse
     */
    public function show(Job $job): View|JsonResponse
    {
        if (request()->expectsJson()) {
            return response()->json($job);
        }

        return view('jobs.show', compact('job'));
    }

    /**
     * Store a newly created job in storage.
     * Only accessible by employers.
     *
     * @param  JobPostRequest  $request
     * @return RedirectResponse
     */
    public function store(JobPostRequest $request): RedirectResponse
    {
       Auth::user()->jobs()->create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary_range' => $request->salary_range,
            'required_skills' => $request->required_skills,
        ]);

        // Clear cache for recent jobs
        Cache::forget('recent_jobs');

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job created successfully!');
    }

    /**
     * Update the specified job in storage.
     * Only accessible by the employer who posted the job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return RedirectResponse
     */
    public function update(JobPostRequest $request, Job $job): RedirectResponse
    {
        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'salary_range' => $request->salary_range,
            'required_skills' => $request->required_skills,
        ]);

        // Clear cache for recent jobs
        Cache::forget('recent_jobs');

        return redirect()->route('employer.jobs.index')->with('success', 'Job updated successfully!');
    }

     /**
     * Remove the specified job from storage.
     * Only accessible by the employer who posted the job.
     *
     * @param  \App\Models\Job  $job
     * @return RedirectResponse
     */
    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        // Clear cache for recent jobs
        Cache::forget('recent_jobs');

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Job deleted successfully!']);
        }

        return redirect()->route('employer.jobs.index')->with('success', 'Job deleted successfully!');
    }
}
