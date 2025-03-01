<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Publication;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;


class PublicationController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Publication::query()->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category', function ($data) {
                        return Category::find($data->category_id)->name ?? 'N/A';
                    })
                    //                    ->addColumn('image', function ($data) {
                    //                        $image = $data->image;
                    //                        $url = asset($image);
                    //                        return '<img src="' . $url . '" alt="image" width="100px" height="100px" style="margin-left:20px;">' ?? 'N/A';
                    //                    })

                    //limit description to 100 characters
                    ->addColumn('description', function ($data) {
                        $description = strip_tags($data->description);
                        return Str::limit($description, 100) ?? 'N/A';
                    })
                    ->addColumn('status', function ($data) {
                        return $data->status === 'published'
                            ? '<span class="badge bg-success text-white">Published</span>'
                            : '<span class="badge bg-danger text-white">Unpublished</span>';
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                            <a href="' . route('admin.publication.edit', $data->id) . '" class="btn btn-primary text-white" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>';
                    })
                    ->rawColumns(['action', 'status', 'category'])
                    ->make(true);
            }
            return view('backend.layout.publication.index');
        } catch (Exception $e) {
            // Handle the exception
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        // Get all categories
        $categories = Category::all();
        return view('backend.layout.publication.create', compact('categories'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'video_url' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'description' => 'required|string',
        ]);

        try {
            // Upload video
            $videoUrl = null;
            if ($request->hasFile('video_url')) {
                $randomString = Str::random(10);
                $videoUrl = Helper::fileUpload($request->file('video_url'), 'articles/video', $randomString);
            }

            //upload document
            $documentUrl  = '';
            if ($request->hasFile('document')) {
                $randomString = Str::random(10);
                $documentUrl = Helper::fileUpload($request->file('document'), 'articles/document', $randomString);
            }


            // Upload image
            $imageUrl = null;
            if ($request->hasFile('image')) {
                $randomString = Str::random(10);
                $imageUrl = Helper::fileUpload($request->file('image'), 'articles/images', $randomString);
            }

            $articles = new Publication();
            $articles->category_id = $request->category_id;
            $articles->title = $request->title;
            $articles->video_url = $videoUrl;
            $articles->image = $imageUrl;

            $articles->document = $documentUrl;
            $articles->description = $request->description;
            $articles->save();

            return redirect()->route('admin.publication.index')->with('t-success', 'Data Created Successfully.');
        } catch (Exception $e) {
            // dd($e);
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        // category
        $categories = Category::all();
        $publication = Publication::findOrFail($id);
        return view('backend.layout.publication.edit', compact('categories', 'publication'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'video_url' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:51200',
            'description' => 'required|string',
        ]);

        try {
            $publication = Publication::with('category')->findOrFail($id);

            // Handle image update
            if ($request->hasFile('image')) {
                // Delete old image
                if (!empty($publication->image) && file_exists(public_path($publication->image))) {
                    unlink(public_path($publication->image));
                }
                // Upload new image
                $randomString = Str::random(10);
                $publication->image = Helper::fileUpload($request->file('image'), 'articles/images', $randomString);
            }

            // Handle document update
            if ($request->hasFile('document')) {
                // Delete old document
                if (!empty($publication->document) && file_exists(public_path($publication->document))) {
                    unlink(public_path($publication->document));
                }
                // Upload new document
                $randomString = Str::random(10);
                $publication->document = Helper::fileUpload($request->file('document'), 'articles/document', $randomString);
            }

            // Handle video update
            if ($request->hasFile('video_url')) {
                // Delete old video
                if (!empty($publication->video_url) && file_exists(public_path($publication->video_url))) {
                    unlink(public_path($publication->video_url));
                }
                // Upload new video
                $randomString = Str::random(10);
                $videoUrl = Helper::fileUpload($request->file('video_url'), 'articles/video', $randomString);
                $publication->video_url = $videoUrl;
            }

            // Update other fields
            $publication->title = $request->title ?? $publication->title;
            $publication->category_id = $request->category_id ?? $publication->category_id;
            $publication->description = $request->description ?? $publication->description;
            $publication->save();

            return redirect()->route('admin.publication.index')->with('t-success', 'Data updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating publication with ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $publication = Publication::findOrFail($id);

            if (empty($publication)) {
                return response()->json(['error' => 'Publication not found'], 404);
            }

            // Delete files
            if (!empty($publication->image)) {
                Helper::fileDelete($publication->image);
            }
            if (!empty($publication->video_url)) {
                Helper::fileDelete($publication->video_url);
            }
            //delete document
            if (!empty($publication->document)) {
                Helper::fileDelete($publication->document);
            }
            // Delete the publication record
            $publication->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Error deleting publication with ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'something went To wrong']);
        }
    }
}
