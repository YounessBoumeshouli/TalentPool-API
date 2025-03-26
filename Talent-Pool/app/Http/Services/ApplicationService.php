<?php
namespace App\Http\Services;

use App\Http\Repository\ApplicationRepository;
use App\Models\Application;
use Illuminate\Support\Facades\Validator;

class ApplicationService
{
    protected $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->$applicationRepository = $applicationRepository;
    }

    public function validateApplicationData(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status' => 'in:active,closed'
        ]);
    }

    public function createApplication(array $data)
    {
        $validator = $this->validateApplicationData($data);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $this->applicationRepository->createApplication($data);
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
