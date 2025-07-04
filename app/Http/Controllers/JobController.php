<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // For caching example

class JobController extends Controller
{
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
}
