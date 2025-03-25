<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPosting;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Info(
 *     title="Recruitment API",
 *     version="1.0.0",
 *     description="API for managing job postings and applications"
 * )
 */
class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_posting_id' => 'required|exists:job_postings,id',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cvPath = $request->file('cv')->store('cvs');
        $coverLetterPath = $request->file('cover_letter')->store('cover_letters');

        $application = Application::create([
            'job_posting_id' => $request->job_posting_id,
            'user_id' => Auth::id(),
            'cv_path' => $cvPath,
            'cover_letter_path' => $coverLetterPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully',
            'application' => $application
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,reviewing,interview,accepted,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $application = Application::find($id);

        if (!$application) {
            return response()->json([
                'status' => 'error',
                'message' => 'Application not found',
            ], 404);
        }

        $application->status = $request->status;
        $application->save();

        // Send notification to candidate
        $user = User::find($application->user_id);
        $jobPosting = JobPosting::find($application->job_posting_id);

        // Create notification in database
        Notification::create([
            'user_id' => $application->user_id,
            'application_id' => $application->id,
            'message' => "Your application for {$jobPosting->title} has been updated to {$request->status}",
        ]);

        // Send email notification (would implement actual email sending here)
        // Mail::to($user->email)->send(new ApplicationStatusChanged($application));

        return response()->json([
            'status' => 'success',
            'message' => 'Application status updated successfully',
            'application' => $application
        ]);
    }
}
