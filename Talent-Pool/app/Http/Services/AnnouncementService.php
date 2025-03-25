<?php
namespace App\Services;

use App\Repositories\AnnouncementRepository;
use Illuminate\Support\Facades\Validator;

class AnnouncementService
{
    protected $announcementRepository;

    public function __construct(AnnouncementRepository $announcementRepository)
    {
        $this->announcementRepository = $announcementRepository;
    }

    public function validateAnnouncementData(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'in:active,closed'
        ]);
    }

    public function createAnnouncement(array $data)
    {
        $validator = $this->validateAnnouncementData($data);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $this->announcementRepository->createAnnouncement($data);
    }

    public function updateAnnouncement($id, array $data)
    {
        $validator = $this->validateAnnouncementData($data);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $this->announcementRepository->updateAnnouncement($id, $data);
    }
}
