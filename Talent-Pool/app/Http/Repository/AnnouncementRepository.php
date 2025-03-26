<?php
namespace App\Http\Repository;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementRepository
{
    public function getAllAnnouncements()
    {
        return Announcement::where('status', 'active')->get();
    }

    public function getAnnouncementById($id)
    {
        return Announcement::findOrFail($id);
    }

    public function createAnnouncement(array $data)
    {
        $data['recruiter_id'] = Auth::id();
        return Announcement::create($data);
    }

    public function updateAnnouncement($id, array $data)
    {
        $announcement = $this->getAnnouncementById($id);
        $announcement->update($data);
        return $announcement;
    }

    public function deleteAnnouncement($id)
    {
        $announcement = $this->getAnnouncementById($id);
        $announcement->delete();
        return true;
    }

    public function getRecruiterAnnouncements()
    {
        return Announcement::where('recruiter_id', Auth::id())->get();
    }
}
