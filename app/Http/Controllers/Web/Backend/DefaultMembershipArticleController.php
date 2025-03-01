<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Enums\Page;
use App\Models\CMS;
use App\Enums\Section;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefaultMembershipArticleController extends Controller
{
    // Display the form for editing the CMS membership article
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        // Retrieve the CMS article based on page and section
        $article = CMS::where('page', Page::MEMBERSHIP->value)
            ->where('section', Section::MEMBERSHIP_DEFAULT_ARTICLE->value)
            ->first();

        // Return the view with the article data
        return view('backend.layout.cms.membership.default', compact('article'));
    }

    // Update the CMS membership article
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'title' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        try {
            // Add static data for page and section
            $validatedData['page'] = Page::MEMBERSHIP->value;
            $validatedData['section'] = Section::MEMBERSHIP_DEFAULT_ARTICLE->value;

            // Check if the CMS article exists and update or create it
            CMS::updateOrCreate(
                [
                    'page' => $validatedData['page'],
                    'section' => $validatedData['section']
                ],
                $validatedData
            );

            // Redirect back to the index page with a success message
            return redirect()->route('admin.cms.default.membership.article.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            // In case of an error, redirect back with the error message
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
