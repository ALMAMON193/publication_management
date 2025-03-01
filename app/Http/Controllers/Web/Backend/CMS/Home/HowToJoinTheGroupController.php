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

class HowToJoinTheGroupController extends Controller
{
    public function index(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
    {
        if ($request->ajax()) {
            $data = CMS::where('page', Page::HOME->value)->where('section', Section::HOW_TO_THE_GROUP_LIST->value)->orderByDesc('created_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = $data->image ? asset($data->image) : asset('backend/images/image-not.png');
                    return '<img src="' . $url . '" alt="image" class="img-fluid" style="height:50px; width:50px">';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                       <a href="' . route('admin.cms.home.how.join.group.edit', $data->id) . '" class="btn btn-primary text-white" title="View">
                           <i class="bi bi-pencil"></i>
                       </a>
                       <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                           <i class="fa fa-times"></i>
                       </a>
                   </div>';
                })
                ->rawColumns(['action', 'image'])
                ->make();
        }
        $group = CMS::where('page', Page::HOME->value)->where('section', Section::HOW_TO_THE_GROUP->value)->first();
        return view('backend.layout.cms.home.join_group.index', compact('group'));
    }
    public function create(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('backend.layout.cms.home.join_group.create');
    }
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        try {
            $validatedData['page'] = Page::HOME->value;
            $validatedData['section'] = Section::HOW_TO_THE_GROUP_LIST->value;
            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();
            $file = 'image';
            if ($request->hasFile($file)) {
                $randomString = Str::random(10);
                $validatedData[$file] = Helper::fileUpload($request->file($file), 'cms/home', $randomString);
            }
            CMS::create($validatedData);
            return redirect()->route('admin.cms.home.how.join.group.index')->with('t-success', 'CMS created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
    //edit why chose up content point
    public function edit($id): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $data = CMS::where('page', Page::HOME->value)
            ->where('section', Section::HOW_TO_THE_GROUP_LIST->value)
            ->first();
        return view('backend.layout.cms.home.join_group.edit', compact('data'));
    }
    //update why chose up content point
    public function update($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {

            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('id', $id)->first();
            if (!$cms) {
                return redirect()->back()->with('t-error', 'CMS not found');
            }
            $file = 'image';
            if ($request->hasFile($file)) {
                // Delete the old file from the filesystem if it exists
                if (isset($cms->{$file}) && file_exists(public_path($cms->{$file}))) {
                    unlink(public_path($cms->{$file}));
                }
                // Upload the new file
                $randomString = Str::random(10);
                $validatedData[$file] = Helper::fileUpload($request->file($file), 'cms/home', $randomString);
            }
            // Update the CMS record
            $cms->update($validatedData);
            return redirect()->route('admin.cms.home.how.join.group.index')->with('t-success', 'CMS updated successfully');
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
            'btn_text' => 'nullable'
        ]);
        try {
            $validatedData['page'] = Page::HOME->value;
            $validatedData['section'] = Section::HOW_TO_THE_GROUP->value;
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
            return redirect()->route('admin.cms.home.how.join.group.index')->with('t-success', 'CMS updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
}
