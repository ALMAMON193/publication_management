<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CorePublication;
use App\Models\KeyDocument;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class KeyDocumentController extends Controller
{

    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {

        try {
            if ($request->ajax()) {
                $data = KeyDocument::latest()->get();
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
                       <a href="' . route('admin.key.document.edit', $data->id) . '" class="btn btn-primary text-white" title="Edit">
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
            return view('backend.layout.key_document.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }
    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        return view('backend.layout.key_document.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'document' => 'required|file|mimes:doc,docx,pdf'
        ]);
        try {
            //upload document
            $documentUrl  = '';
            if ($request->hasFile('document')) {
                $randomString = Str::random(10);
                $documentUrl = Helper::fileUpload($request->file('document'), 'key_document', $randomString);
            }
            $keyDocument = new KeyDocument();
            $keyDocument->document = $documentUrl;
            $keyDocument->save();
            return redirect()->route('admin.key.document.index')->with('t-success', 'Data Created Successfully.');
        } catch (Exception $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $keyDocument = KeyDocument::find($id);
        return view('backend.layout.key_document.edit', compact('keyDocument'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'document' => 'nullable|file|mimes:doc,docx,pdf',
        ]);
        try {
            $keyDocument = KeyDocument::findOrFail($id);
            // Delete old document if new one is provided
            if (!empty($keyDocument->document)) {
                Helper::fileDelete($keyDocument->document);
            }
           // Handle the new document upload
            if ($request->hasFile('document')) {
                $randomString = Str::random(10);
                $documentUrl = Helper::fileUpload($request->file('document'), 'key_document', $randomString);
                $keyDocument->document = $documentUrl;
            }

            $keyDocument->save();
            return redirect()->route('admin.key.document.index')->with('t-success', 'Data updated successfully.');
        }  catch (Exception $e) {
            Log::error('Error updating publication with ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $keyDocument = KeyDocument::findOrFail($id);
        //Delete document
        if (!empty($keyDocument->document)) {
            Helper::fileDelete($keyDocument->document);
        }
        $keyDocument->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
    }
}
