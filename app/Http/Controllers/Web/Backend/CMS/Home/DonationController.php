<?php

namespace App\Http\Controllers\Web\Backend\CMS\Home;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $donation = CMS::where('page', Page::HOME->value)->where('section', Section::HOME_DONATION->value)->first();
        return view('backend.layout.cms.home.donation.index', compact('donation'));
    }
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'content' => 'nullable',

        ]);

        try {
            $validatedData['page'] = Page::HOME->value;
            $validatedData['section'] = Section::HOME_DONATION->value;

            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();

            // Handle the background image
            $file = 'background';
            if ($request->hasFile($file)) {
                // Delete the old file from the filesystem if it exists
                if ($cms && isset($cms->{$file}) && file_exists(public_path($cms->{$file}))) {
                    unlink(public_path($cms->{$file}));
                }
                // Upload the new file
                $randomString = Str::random(10);
                $validatedData[$file] = Helper::fileUpload($request->file($file), 'cms/home', $randomString);
            }
            // Create or update the CMS record
            CMS::updateOrCreate(
                ['page' => $validatedData['page'], 'section' => $validatedData['section']],
                $validatedData
            );
            return redirect()->route('admin.cms.home.donation.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
