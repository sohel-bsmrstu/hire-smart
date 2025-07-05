<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Job;

class EmployerJobAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $job = $request->route('job');

        if (!$job instanceof Job) {
            $jobId = $request->route('job');
            $job = Job::find($jobId);
        }

        if (!$job || Auth::user()->id !== $job->user_id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized: You do not own this job listing.'], 403);
            }
            
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
