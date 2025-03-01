<?php

namespace App\Http\Controllers\Web\Backend\CMS\Membership;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\CorePublication;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MembershipController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->ajax()) {
            $data = CMS::where('page', Page::MEMBERSHIP->value)
                ->where('section', Section::MEMBERSHIP_CONTENT->value)
                ->orderBy('created_at', 'asc') // Ascending order
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()

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
                       <a href="' . route('admin.cms.membership.edit', $data->id) . '" class="btn btn-primary text-white" title="View">
                           <i class="bi bi-pencil"></i>
                       </a>
                       <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                           <i class="fa fa-times"></i>
                       </a>
                   </div>';
                })
                ->rawColumns(['action','content','title'])
                ->make();
        }
        return view('backend.layout.cms.membership.index');
    }

    public function create(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('backend.layout.cms.membership.create');
    }
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'title' =>'nullable',
            'content' =>'nullable'
        ]);
        try {
            $validatedData['page'] = Page::MEMBERSHIP->value;
            $validatedData['section'] = Section::MEMBERSHIP_CONTENT->value;
            // Fetch the current CMS record (if it exists)
            $cms = CMS::where('page', $validatedData['page'])
                ->where('section', $validatedData['section'])
                ->first();
            CMS::create($validatedData);
            return redirect()->route('admin.cms.membership.index')->with('t-success', 'CMS created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
    //edit why chose up content point
    public function edit($id): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $data = CMS::where('page', Page::MEMBERSHIP->value)
            ->where('section', Section::MEMBERSHIP_CONTENT->value)
            ->first();
        return view('backend.layout.cms.membership.edit', compact('data'));
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
            return redirect()->route('admin.cms.membership.index')->with('t-success', 'CMS updated successfully');
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
                return response()->json(['success'=>false, 'message'=>'Data could not be retrieved']);
            }
            // Delete the CMS record from the database
            $cms->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

}
