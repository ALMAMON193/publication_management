<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PublicationCategoryController extends Controller
{

    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {

        try {
            if ($request->ajax()) {
                $data = Category::latest()->get()->sortByDesc('created_at');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($data) {
                        return $data->created_at->format('Y-m-d H:i:s');
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                       <a href="' . route('admin.category.edit', $data->id) . '" class="btn btn-primary text-white" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>';
                    })
                    ->rawColumns(['document', 'action', 'created_at'])
                    ->make(true);
            }
            return view('backend.layout.category.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('backend.layout.category.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.category.index')->with('t-success', 'Data Created Successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $category = Category::findOrFail($id);
        return view('backend.layout.category.edit', compact('category'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        try {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->save();
            return redirect()->route('admin.category.index')->with('t-success', 'Data updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating Category with ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $category = Category::latest()->findOrFail($id);
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}
