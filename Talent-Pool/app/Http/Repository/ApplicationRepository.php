<?php
namespace App\Http\Repository;

use App\Models\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ApplicationRepository
{
    public function getAllApplications()
    {
        return Application::where('status', 'active')->get();
    }

    public function getApplicationById($id)
    {
        return Application::findOrFail($id);
    }

    public function createApplication(array $data)
    {
        $data['recruiter_id'] = Auth::id();
        return Application::create($data);
    }

    public function updateApplication($id, array $data)
    {
        $application = $this->getApplicationById($id);
        $application->update($data);
        return $application;
    }

    public function deleteApplication($id)
    {
        $application = $this->getApplicationById($id);
        $application->delete();
        return true;
    }

    public function getCandidateApplications()
    {
        return Application::where('candidate_id', Auth::id())->get();
    }
}
