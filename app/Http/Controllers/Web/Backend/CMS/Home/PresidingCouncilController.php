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

class PresidingCouncilController extends Controller
{
    public function index(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        $presiding = CMS::where('page', Page::HOME->value)->where('section', Section::PRESIDING_COUNCIL->value)->first();
        return view('backend.layout.cms.home.presiding_council.index', compact('presiding'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'sub_title' => 'nullable',
            'content' => 'nullable',
        ]);

        try {
            $validatedData['page'] = Page::HOME->value;
            $validatedData['section'] = Section::PRESIDING_COUNCIL->value;
            CMS::updateOrCreate(
                ['page' => $validatedData['page'], 'section' => $validatedData['section']],
                $validatedData
            );
            return redirect()->route('admin.cms.home.presiding.council.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
