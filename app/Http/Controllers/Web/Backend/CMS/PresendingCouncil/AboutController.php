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

class AboutController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $about = CMS::where('page', Page::PRESIDING_COUNCIL->value)->where('section', Section::PRESIDING_COUNCIL_ABOUT->value)->first();
        return view('backend.layout.cms.presiding_council.about.index', compact('about'));
    }
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'content' => 'nullable',
        ]);

        try {
            $validatedData['page'] = Page::PRESIDING_COUNCIL->value;
            $validatedData['section'] = Section::PRESIDING_COUNCIL_ABOUT->value;

            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();
            // Create or update the CMS record
            CMS::updateOrCreate(
                ['page' => $validatedData['page'], 'section' => $validatedData['section']],
                $validatedData
            );
            return redirect()->route('admin.cms.presiding.council.about.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
