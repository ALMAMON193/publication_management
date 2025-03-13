<?php

namespace App\Http\Controllers\API\CMS;

use Exception;
use App\Enums\Page;
use App\Models\CMS;
use App\Enums\Section;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class KeyDocumentController extends Controller
{
    /**===============================key document api=============================== */

    public function Banner(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = CMS::where('page', Page::KEY_DOCUMENT)->where('section', Section::KEY_DOCUMENT_BANNER)->select('id', 'title', 'sub_title', 'content', 'background')->first();
           
            return Helper::jsonResponse(true, 'Key Document Banner Data Fetch Successfully', 200, $data  ?? []);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
}
