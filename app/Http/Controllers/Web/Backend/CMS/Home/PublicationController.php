<?php

namespace App\Http\Controllers\Web\Backend\CMS\Home;

use Exception;
use App\Enums\Page;
use App\Models\CMS;
use App\Enums\Section;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PublicationController extends Controller
{
    public function index(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        $publication = CMS::where('page', Page::HOME->value)->where('section', Section::CORE_PUBLICATION->value)->first();
        return view('backend.layout.cms.home.core_publication.index', compact('publication'));
    }
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'background' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            $validatedData['page'] = Page::HOME->value;
            $validatedData['section'] = Section::CORE_PUBLICATION->value;
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();
            foreach (['background', 'image'] as $file) {
                if ($request->hasFile($file)) {
                    if ($cms && isset($cms->{$file}) && file_exists(public_path($cms->{$file}))) {
                        unlink(public_path($cms->{$file}));
                    }
                    $randomString = Str::random(10);
                    $validatedData[$file] = Helper::fileUpload($request->file($file), 'cms/home', $randomString);
                }
            }
            CMS::updateOrCreate(
                ['page' => $validatedData['page'], 'section' => $validatedData['section']],
                $validatedData
            );
            return redirect()->route('admin.cms.home.core.publication.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
