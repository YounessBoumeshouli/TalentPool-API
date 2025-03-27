<?php
namespace App\Http\Controllers;

use App\Http\Services\ApplicationService;
use App\Http\Repository\ApplicationRepository;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected $applicationService;
    protected $applicationRepository;

    public function __construct(
        ApplicationService $applicationService,
        ApplicationRepository $applicationRepository
    ) {
        $this->applicationService = $applicationService;
        $this->applicationRepository = $applicationRepository;
    }

    public function index()
    {
        return response()->json([
            'applications' => $this->applicationRepository->getCandidateApplications()
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'application' => $this->applicationRepository->getApplicationById($id)
        ]);
    }

    public function store(Request $request)
    {
        try {
            $application = $this->applicationService->createApplication($request->all());
            return response()->json([
                'message' => 'Application created successfully',
                'application' => $application
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $application = $this->applicationService->updateApplication($id, $request->all());
            return response()->json([
                'message' => 'Application updated successfully',
                'application' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        $this->applicationRepository->deleteAnnouncement($id);
        return response()->json([
            'message' => 'Application deleted successfully'
        ]);
    }

    public function myApplications()
    {
        return response()->json([
            'applications' => $this->applicationRepository->getRecruiterApplications()
        ]);
    }
}
