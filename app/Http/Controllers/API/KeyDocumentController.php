<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\KeyDocument;
use Illuminate\Http\Request;

class KeyDocumentController extends Controller
{
    function KeyDocument(): \Illuminate\Http\JsonResponse
    {
        // Fetch Core Publications from API and return them
        $data = KeyDocument::paginate(5);
        return Helper::jsonResponse(true,'Fetch Key Document Successfully',200,$data);
    }
}
