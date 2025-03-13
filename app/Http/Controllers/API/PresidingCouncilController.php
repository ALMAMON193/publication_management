<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\PresidingCouncil;
use App\Models\Publication;
use Illuminate\Http\Request;

class PresidingCouncilController extends Controller
{
    function PresidingCouncil(): \Illuminate\Http\JsonResponse
    {
        // Fetch presiding Council from API and return them
        $data = PresidingCouncil::all();
        $data->makeHidden(['status', 'updated_at', 'created_at']);
        return Helper::jsonResponse(true, 'Fetch Presiding Council Data', 200, $data);
    }
}
