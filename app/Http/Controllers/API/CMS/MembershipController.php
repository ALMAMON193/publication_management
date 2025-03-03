<?php

namespace App\Http\Controllers\API\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\Membership;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MembershipController extends Controller
{
    public function Content(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = CMS::where('page', Page::MEMBERSHIP)
                ->where('section', Section::MEMBERSHIP_CONTENT)
                ->select('id', 'title', 'content')
                ->get();

            return Helper::jsonResponse(true, 'Membership Data Fetch Successfully', 200, $data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    public function defaultArticle(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = CMS::where('page', Page::MEMBERSHIP)
                ->where('section', Section::MEMBERSHIP_DEFAULT_ARTICLE)
                ->select('id', 'title', 'content', 'video')
                ->get();
            return Helper::jsonResponse(true, 'Membership Default Article Data Fetch Successfully', 200, $data);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }

    public function GetMembership(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = Membership::where('status', 'active')->get()->map(function ($item) {
                $item->description = strip_tags($item->description); // Remove all HTML tags
                return $item;
            });;
            return Helper::jsonResponse(true, 'Membership Form Data Fetch Successfully', 200, $data  ?? []);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
}
