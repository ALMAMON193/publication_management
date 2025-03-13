<?php

namespace App\Http\Controllers\Web\Backend\CMS\PresendingCouncil;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $banner = CMS::where('page', Page::PRESIDING_COUNCIL->value)->where('section', Section::PRESIDING_COUNCIL_BANNER->value)->first();
        return view('backend.layout.cms.presiding_council.banner.index', compact('banner'));
    }
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'content' => 'nullable',
            'background' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            $validatedData['page'] = Page::PRESIDING_COUNCIL->value;
            $validatedData['section'] = Section::PRESIDING_COUNCIL_BANNER->value;

            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();

            // Handle the background image
            if ($request->hasFile('background')) {
                if ($cms && isset($cms->background) && file_exists(public_path($cms->background))) {
                    unlink(public_path($cms->background));
                }
                $randomString = Str::random(10);
                $validatedData['background'] = Helper::fileUpload($request->file('background'), 'cms/presiding_council', $randomString);
            }

            // Handle the image
            if ($request->hasFile('image')) {
                if ($cms && isset($cms->image) && file_exists(public_path($cms->image))) {
                    unlink(public_path($cms->image));
                }
                $randomString = Str::random(10);
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'cms/presiding_council', $randomString);
            }
            // Create or update the CMS record
            CMS::updateOrCreate(
                ['page' => $validatedData['page'], 'section' => $validatedData['section']],
                $validatedData
            );

            return redirect()->route('admin.cms.presiding.council.banner.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
