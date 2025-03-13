<?php

namespace App\Http\Controllers\API\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresidingCouncilController extends Controller
{
    public function Banner(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = CMS::where('page', Page::PRESIDING_COUNCIL)->where('section', Section::PRESIDING_COUNCIL_BANNER)->select('id', 'title', 'content', 'background', 'image')->first();
           
            return Helper::jsonResponse(true, 'Presiding Council Banner Data Fetch Successfully', 200, $data ?? []);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    public function About(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = CMS::where('page', Page::PRESIDING_COUNCIL)->where('section', Section::PRESIDING_COUNCIL_ABOUT)->select('id', 'title', 'content')->first();
            return Helper::jsonResponse(true, 'Presiding Council About Data Fetch Successfully', 200, $data ?? []);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
}
