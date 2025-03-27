<?php
namespace App\Http\Services;

use App\Http\Repository\ApplicationRepository;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicationService
{
    protected $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function validateApplicationData(array $data)
    {
        return Validator::make($data, [
            'announcement_id' => 'required|exists:announcements,id',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max file size
            'motivation_letter' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);
    }

    public function createApplication(array $data)
    {
        $validator = $this->validateApplicationData($data);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Handle file uploads
        $cvPath = $data['cv']->store('cvs', 'public');
        $motivationLetterPath = $data['motivation_letter']->store('motivation_letters', 'public');

        // Prepare data for repository
        $applicationData = [
            'announcement_id' => $data['announcement_id'],
            'cv_path' => $cvPath,
            'motivation_letter_path' => $motivationLetterPath,
            'candidate_id'=>Auth::id()
        ];

        return $this->applicationRepository->createApplication($applicationData);
    }

    public function updateApplication($id, array $data)
    {
        $validator = $this->validateApplicationData($data);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $this->applicationRepository->updateApplication($id, $data);
    }
}
