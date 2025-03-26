<?php
namespace App\Http\Controllers;

use App\Http\Services\AnnouncementService;
use App\Http\Repository\AnnouncementRepository;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    protected $announcementService;
    protected $announcementRepository;

    public function __construct(
        AnnouncementService $announcementService,
        AnnouncementRepository $announcementRepository
    ) {
        $this->announcementService = $announcementService;
        $this->announcementRepository = $announcementRepository;
    }

    public function index()
    {
        return response()->json([
            'announcements' => $this->announcementRepository->getAllAnnouncements()
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'announcement' => $this->announcementRepository->getAnnouncementById($id)
        ]);
    }

    public function store(Request $request)
    {
        try {
            $announcement = $this->announcementService->createAnnouncement($request->all());
            return response()->json([
                'message' => 'Announcement created successfully',
                'announcement' => $announcement
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
            $announcement = $this->announcementService->updateAnnouncement($id, $request->all());
            return response()->json([
                'message' => 'Announcement updated successfully',
                'announcement' => $announcement
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        $this->announcementRepository->deleteAnnouncement($id);
        return response()->json([
            'message' => 'Announcement deleted successfully'
        ]);
    }

    public function myAnnouncements()
    {
        return response()->json([
            'announcements' => $this->announcementRepository->getRecruiterAnnouncements()
        ]);
    }
}
