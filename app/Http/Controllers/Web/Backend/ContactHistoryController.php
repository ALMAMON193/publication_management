<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ContactHistoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = Contact::latest('created_at');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('message', function ($data) {
                        return Str::limit($data->message, 50) ?? 'N/A';
                    })
                    ->addColumn('subject', function ($data) {
                        return Str::limit($data->message, 50) ?? 'N/A';
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group">
                         <a href="' . route('admin.contact-hiostory.view', $data->id) . '" class="btn btn-primary text-white" title="Edit">
                            <i class="bi bi-eye"></i>
                        </a>
                           <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                               <i class="fa fa-times"></i>
                           </a>
                          
                       </div>';
                    })
                    ->rawColumns(['action', 'message'])
                    ->make(true);
            }

            return view('backend.layout.contact_history.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }
    public function delete($id)
    {
        try {
            $data = Contact::find($id);
            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Membership not found.'], 404);
            }

            // Delete the user membership
            $data->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong! Please try again.'], 500);
        }
    }

    //view contact details 

    public function view($id){
        $data = Contact::find($id);
        return view('backend.layout.contact_history.view',compact('data'));
    }



}
