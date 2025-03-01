<?php

namespace App\Http\Controllers\API\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\CorePublication;
use App\Models\PresidingCouncil;
use Exception;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /************************* Banner Start ************************/
    public function Banner(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HOME)->where('section', Section::HOME_BANNER)->select('id', 'title', 'content', 'background', 'image')->first();
            
            return Helper::jsonResponse(true, 'Banner Data Fetch Successfully', 200, $data ?? []);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    /************************* Banner End ************************/
    /************************* About Start ************************/
    public function About(): \Illuminate\Http\JsonResponse
    {
        try {
            $about = CMS::where('page', Page::HOME)
                ->where('section', Section::HOME_ABOUT)
                ->select('id', 'title', 'content', 'image', 'btn_text')
                ->first();

            $aboutItem = CMS::where('page', Page::HOME)
                ->where('section', Section::HOME_ABOUT_ITEM)
                ->select('id', 'title', 'image')
                ->get();

            return Helper::jsonResponse(true, 'About Data Fetch Successfully', 200, [
                'about' => $about,
                'about_item' => $aboutItem
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    /************************* About End ************************/
    /************************* Core History Start ************************/
    public function History(): \Illuminate\Http\JsonResponse
    {
        try {
            $history = CMS::where('page', Page::HOME)
                ->where('section', Section::HOW_HISTORY)
                ->select('id', 'title', 'content', 'image')
                ->first();

            $history_item = CMS::where('page', Page::HOME)
                ->where('section', Section::HOW_HISTORY_ITEM)
                ->select('id', 'title', 'content')
                ->get();
            return Helper::jsonResponse(true, 'Core History Data Fetch Successfully', 200, [
                'history' => $history,
                'history_item' => $history_item,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    public function JoinGroup(): \Illuminate\Http\JsonResponse
    {
        try {
            $content = CMS::where('page', Page::HOME)
                ->where('section', Section::HOW_TO_THE_GROUP)
                ->select('id', 'title', 'content', 'image', 'btn_text')
                ->first();

            $joining_item = CMS::where('page', Page::HOME)
                ->where('section', Section::HOW_TO_THE_GROUP_LIST)
                ->select('id', 'title', 'image')
                ->get();
            return Helper::jsonResponse(true, 'Join Group Data Fetch Successfully', 200, [
                'content' => $content,
                'joining_item' => $joining_item,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    public function CorePublication(): \Illuminate\Http\JsonResponse
    {
        try {
            $content = CMS::where('page', Page::HOME)
                ->where('section', Section::CORE_PUBLICATION)
                ->select('id', 'title', 'image', 'background')
                ->first();

            $corePublication = CorePublication::select('id', 'title', 'document')->orderBy('created_at', 'desc')->get();
            return Helper::jsonResponse(true, 'Core Publication Data Fetch Successfully', 200, [
                'content' => $content,
                'list_documents' => $corePublication,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    public function PresidingCouncil(): \Illuminate\Http\JsonResponse
    {
        try {
            $content = CMS::where('page', Page::HOME)
                ->where('section', Section::PRESIDING_COUNCIL)
                ->select('id', 'title', 'content', 'sub_title')
                ->first();

            $presidingCouncil = PresidingCouncil::all();
            return Helper::jsonResponse(true, 'Presiding Council Data Fetch Successfully', 200, [
                'content' => $content,
                'presiding_members' => $presidingCouncil,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
    public function Donation(): \Illuminate\Http\JsonResponse
    {
        try {
            $content = CMS::where('page', Page::HOME)
                ->where('section', Section::HOME_DONATION)
                ->select('id', 'title', 'content', 'btn_text')
                ->first();

            return Helper::jsonResponse(true, 'Donation Data Fetch Successfully', 200, [
                'content' => $content ?? [],
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong', 500);
        }
    }
}
