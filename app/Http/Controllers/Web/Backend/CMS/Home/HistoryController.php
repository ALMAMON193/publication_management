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
use Yajra\DataTables\DataTables;

class HistoryController extends Controller
{
    public function index(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        if ($request->ajax()) {
            $data = CMS::where('page', Page::HOME->value)->where('section', Section::HOW_HISTORY_ITEM->value)->orderByDesc('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = $data->image ? asset($data->image) : asset('backend/images/image-not.png');
                    return '<img src="' . $url . '" alt="image" class="img-fluid" style="height:50px; width:50px">';
                })
                ->addColumn('content', function ($data) {
                    // Strip HTML tags and truncate the content
                    $content = strip_tags($data->content);
                    return Str::limit($content, 100);
                })
                ->addColumn('title', function ($data) {
                    // Strip HTML tags and truncate the content
                    $title = strip_tags($data->title);
                    return Str::limit($title, 100);
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                       <a href="' . route('admin.cms.home.history.edit', $data->id) . '" class="btn btn-primary text-white" title="View">
                           <i class="bi bi-pencil"></i>
                       </a>
                       <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                           <i class="fa fa-times"></i>
                       </a>
                   </div>';
                })
                ->rawColumns(['action', 'content', 'title'])
                ->make();
        }
        $history = CMS::where('page', Page::HOME->value)->where('section', Section::HOW_HISTORY->value)->first();
        return view('backend.layout.cms.home.history.index', compact('history'));
    }
    public function create(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('backend.layout.cms.home.history.create');
    }
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'content' => 'nullable'
        ]);
        try {
            $validatedData['page'] = Page::HOME->value;
            $validatedData['section'] = Section::HOW_HISTORY_ITEM->value;
            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();
            CMS::create($validatedData);
            return redirect()->route('admin.cms.home.history.index')->with('t-success', 'CMS created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
    //edit why chose up content point
    public function edit($id): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $data = CMS::where('page', Page::HOME->value)
            ->where('section', Section::HOW_HISTORY_ITEM->value)
            ->find($id);
        return view('backend.layout.cms.home.history.edit', compact('data'));
    }
    //update why chose up content point
    public function update($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'content' => 'nullable'
        ]);

        try {

            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('id', $id)->first();
            if (!$cms) {
                return redirect()->back()->with('t-error', 'CMS not found');
            }
            // Update the CMS record
            $cms->update($validatedData);
            return redirect()->route('admin.cms.home.history.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
    //delete why chose up content point
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('id', $id)->first();
            if (!$cms) {
                return response()->json(['success' => false, 'message' => 'Data could not be retrieved']);
            }
            // Delete the CMS record from the database
            $cms->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateContent(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'content' => 'nullable',
        ]);

        try {
            $validatedData['page'] = Page::HOME->value;
            $validatedData['section'] = Section::HOW_HISTORY->value;

            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();
            // Handle the background image
            $file = 'image';
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
            return redirect()->route('admin.cms.home.history.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
