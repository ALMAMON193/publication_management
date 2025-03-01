<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Membership;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Membership::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('description', function ($data) {
                        $description = strip_tags($data->description);
                        return Str::limit($description, 100);
                    })
                    ->addColumn('status', function ($data) {
                        $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                        $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                        if ($data->status === 'active') {
                            $status .= "checked";
                        }
                        $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                        return $status;
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                       <a href="' . route('admin.membership.edit', $data->id) . '" class="btn btn-primary text-white" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>';
                    })
                    ->rawColumns(['action', 'status', 'description'])
                    ->make(true);
            }
            return view('backend.layout.membership.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('backend.layout.membership.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'price' => 'required',
            'duration' => 'required|integer',
            'duration_type' => 'required|in:weeks,months,years'
        ]);
        try {

            $membership = new Membership();
            $membership->name = $request->name;
            $membership->description = $request->description;
            $membership->price = $request->price;
            $membership->duration = $request->duration;
            $membership->duration_type = $request->duration_type;

            $membership->save();
            return redirect()->route('admin.membership.index')->with('t-success', 'Data Created Successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $membership = Membership::find($id);
        return view('backend.layout.membership.edit', compact('membership'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'price' => 'required',
            'duration' => 'required|integer',
            'duration_type' => 'required|in:weeks,months,years'
        ]);
        try {
            $membership = Membership::findOrFail($id);
            $membership->name = $request->name;
            $membership->description = $request->description;
            $membership->price = $request->price;
            $membership->duration = $request->duration;
            $membership->duration_type = $request->duration_type;
            $membership->save();
            return redirect()->route('admin.membership.index')->with('t-success', 'Data updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating publication with ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $keyDocument = Membership::findOrFail($id);
        $keyDocument->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }

    public function status(Request $request, $id): ?\Illuminate\Http\JsonResponse
    {
        $data = Membership::find($id);
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data not found']);
        }

        if ($data->status === 'active') {
            $data->status = 'inactive';
            $data->save();
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data' => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data' => $data,
            ]);
        }
    }
}
