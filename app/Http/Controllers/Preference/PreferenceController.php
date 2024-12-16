<?php

namespace App\Http\Controllers\Preference;

use App\Services\Preference\PreferenceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Preference\CreateRequest;
use App\Http\Requests\Preference\DeleteRequest;
use App\Http\Requests\Preference\UpdateRequest;
use App\Models\Preference\Preference;


class PreferenceController extends Controller
{    
    protected $preferenceService;

    public function __construct(PreferenceService $preferenceService)
    {
        $this->preferenceService = $preferenceService;
    }

    public function create(CreateRequest $request)
    {
        $response = $this->preferenceService->createPreference($request);

        return $response;
    }

    public function list()
    {
        $user_id = auth()->user()->id;
        $response = $this->preferenceService->listPreference($user_id);

        return $response;
    }

    public function update(UpdateRequest $request, Preference $preference)
    {
        $response = $this->preferenceService->updatePreference($request, $preference);

        return $response;
    }

    public function delete(DeleteRequest $request, Preference $preference)
    {
        $response = $this->preferenceService->deletePreference($preference);

        return $response;
    }
}