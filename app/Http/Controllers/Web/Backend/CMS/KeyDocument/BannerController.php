<?php

namespace App\Http\Controllers\Web\Backend\CMS\KeyDocument;

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
        $banner = CMS::where('page', Page::KEY_DOCUMENT->value)->where('section', Section::KEY_DOCUMENT_BANNER->value)->first();
        return view('backend.layout.cms.key_document.banner.index', compact('banner'));
    }
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'content' => 'nullable',
            'background' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            $validatedData['page'] = Page::KEY_DOCUMENT->value;
            $validatedData['section'] = Section::KEY_DOCUMENT_BANNER->value;

            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();

            // Handle the background image
            if ($request->hasFile('background')) {
                // Delete the old file from the filesystem if it exists
                if ($cms && isset($cms->background) && file_exists(public_path($cms->background))) {
                    unlink(public_path($cms->background));
                }
                // Upload the new file
                $randomString = Str::random(10);
                $validatedData['background'] = Helper::fileUpload($request->file('background'), 'cms/home', $randomString);
            }
            // Create or update the CMS record
            CMS::updateOrCreate(
                ['page' => $validatedData['page'], 'section' => $validatedData['section']],
                $validatedData
            );
            return redirect()->route('admin.cms.key.document.banner.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
