<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CorePublication;
use App\Models\Publication;
use Illuminate\Http\Request;

class CorePublicationController extends Controller
{
    function CorePublications(): \Illuminate\Http\JsonResponse
    {
        // Fetch Core Publications from API and return them
        $data = CorePublication::all();
        $data->each(function ($publication) {
            $publication->description = strip_tags($publication->description);
        });
        $data->makeHidden(['status', 'updated_at', 'created_at']);
        return Helper::jsonResponse(true, 'Fetch Core Publications', 200, $data);
    }
}
