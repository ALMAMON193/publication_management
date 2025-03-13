<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PresidingCouncil;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PresidingController extends Controller
{

    public function index(Request $request)
    {

        try {
            if ($request->ajax()) {
                $data = PresidingCouncil::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('image', function ($data) {
                        $image = $data->image;
                        $url = asset($image);
                        return '<img src="' . $url . '" alt="image" width="100px" height="100px" style="margin-left:20px;">';
                    })
                    ->addColumn('bio', function ($data) {
                        // Strip HTML tags and truncate the content
                        $bio = strip_tags($data->bio);
                        return Str::limit($bio, 100);
                    })

                    ->addColumn('action', function ($data) {
                        $editUrl = route('admin.presiding_councils.edit', $data->id);
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                        <a href="' . $editUrl . '" class="btn btn-primary text-white" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>';
                    })

                    ->rawColumns(['image', 'bio', 'action'])
                    ->make(true);
            }
            return view('backend.layout.presiding_councils.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('backend.layout.presiding_councils.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'bio' => 'required|string',
            'designation_type' => 'required|in:Councilman,Vice Chairperson,Chairman',
        ]);
        try {
            $imageUrl = '';
            if ($request->hasFile('image')) {
                // Upload the new file
                $randomString = Str::random(10);
                $imageUrl = Helper::fileUpload($request->file('image'), 'presiding_council', $randomString);
            }
            $PresidingCouncil = new PresidingCouncil();
            $PresidingCouncil->name = $request->name;
            $PresidingCouncil->designation = $request->designation;
            $PresidingCouncil->image = $imageUrl;
            $PresidingCouncil->bio = $request->bio;
            $PresidingCouncil->designation_type = $request->designation_type;
            $PresidingCouncil->save();

            return redirect()->route('admin.presiding_councils.index')->with('t-success', 'Presiding Council Created Successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $data = PresidingCouncil::find($id);

        return view('backend.layout.presiding_councils.edit', compact('data'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {


        $request->validate([
            'name' => 'string|max:255',
            'designation' => 'string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'bio' => 'string',
            'designation_type' => 'required|in:Councilman,Vice Chairperson,Chairman',
        ]);
        try {
            $PresidingCouncil = PresidingCouncil::findOrFail($id);
            if ($request->hasFile('image')) {
                if (!empty($PresidingCouncil->image)) {
                    Helper::fileDelete($PresidingCouncil->image);
                }
                $randomString = Str::random(10);
                $documentUrl = Helper::fileUpload($request->file('image'), 'presiding_council', $randomString);
                $PresidingCouncil->image = $documentUrl;
            }

            $PresidingCouncil->name = $request->name;
            $PresidingCouncil->designation = $request->designation;
            $PresidingCouncil->bio = $request->bio;
            $PresidingCouncil->designation_type = $request->designation_type;
            $PresidingCouncil->save();

            return redirect()->route('admin.presiding_councils.index')->with('t-success', 'Data updated Successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {

        $data = PresidingCouncil::findOrFail($id);
        $imagePath = public_path($data->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}
