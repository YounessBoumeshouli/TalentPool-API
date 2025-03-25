<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/**
 * @OA\Info(
 *     title="Recruitment API",
 *     version="1.0.0",
 *     description="API for managing job postings and applications"
 * )
 */
class StatisticsController extends Controller
{
    public function recruiterStats()
    {
        $userId = Auth::id();

        $jobPostings = JobPosting::where('user_id', $userId)->get();
        $jobPostingIds = $jobPostings->pluck('id');

        $totalApplications = Application::whereIn('job_posting_id', $jobPostingIds)->count();

        $applicationsByStatus = Application::whereIn('job_posting_id', $jobPostingIds)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        $popularJobPostings = Application::whereIn('job_posting_id', $jobPostingIds)
            ->selectRaw('job_posting_id, count(*) as count')
            ->groupBy('job_posting_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'statistics' => [
                'total_job_postings' => $jobPostings->count(),
                'total_applications' => $totalApplications,
                'applications_by_status' => $applicationsByStatus,
                'popular_job_postings' => $popularJobPostings,
            ]
        ]);
    }

    public function globalStats()
    {
        $totalUsers = User::count();
        $totalCandidates = User::where('role', 'candidate')->count();
        $totalRecruiters = User::where('role', 'recruiter')->count();

        $totalJobPostings = JobPosting::count();
        $totalApplications = Application::count();

        $applicationsByStatus = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        $popularJobPostings = Application::selectRaw('job_posting_id, count(*) as count')
            ->groupBy('job_posting_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'statistics' => [
                'total_users' => $totalUsers,
                'total_candidates' => $totalCandidates,
                'total_recruiters' => $totalRecruiters,
                'total_job_postings' => $totalJobPostings,
                'total_applications' => $totalApplications,
                'applications_by_status' => $applicationsByStatus,
                'popular_job_postings' => $popularJobPostings,
            ]
        ]);
    }
}
