<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CorePublication;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CorePublicationController extends Controller
{

    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {

        try {
            if ($request->ajax()) {
                $data = CorePublication::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('document', function ($data) {
                        $document = $data->document;
                        $url = asset($document);
                        return '<embed src="' . $url . '#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100px" height="100px" />';
                    })
                    ->addColumn('created_at', function ($data) {
                        return $data->created_at->format('Y-m-d H:i:s');
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                       <a href="' . route('admin.core_publication.edit', $data->id) . '" class="btn btn-primary text-white" title="Edit">
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
            return view('backend.layout.core_publication.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('backend.layout.core_publication.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'required|file|mimes:doc,docx,pdf'
        ]);
        try {
            //upload document
            $documentUrl  = '';
            if ($request->hasFile('document')) {
                $randomString = Str::random(10);
                $documentUrl = Helper::fileUpload($request->file('document'), 'core_publications', $randomString);
            }

            $publication = new CorePublication();
            $publication->title = $request->title;
            $publication->document = $documentUrl;
            $publication->save();
            return redirect()->route('admin.core_publication.index')->with('t-success', 'Data Created Successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $publication = CorePublication::findOrFail($id);
        return view('backend.layout.core_publication.edit', compact('publication'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        // dd($request->all());
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:doc,docx,pdf',
        ]);
        try {
            $publication = CorePublication::findOrFail($id);
            //Delete document
            if (!empty($publication->document)) {
                Helper::fileDelete($publication->document);
            }
            if ($request->hasFile('document')) {
                $randomString = Str::random(10);
                $documentUrl = Helper::fileUpload($request->file('document'), 'core_publications', $randomString);
                $publication->document = $documentUrl;
            }
            $publication->title = $request->title;
            $publication->save();
            return redirect()->route('admin.core_publication.index')->with('t-success', 'Data updated successfully.');
        } catch (ModelNotFoundException $e) {
            Log::error('Preceding Council not found with ID: ' . $id);
            return redirect()->back()->with('t-error', 'The specified record does not exist.');
        } catch (Exception $e) {
            Log::error('Error updating publication with ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $publication = app(CorePublication::class)->findOrFail($id);
        //Delete document
        if (!empty($publication->document)) {
            Helper::fileDelete($publication->document);
        }
        $publication->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}
