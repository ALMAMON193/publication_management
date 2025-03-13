<?php

namespace App\Http\Controllers\Web\Backend;


use Exception;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class MembershipHistoryController extends Controller
{

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = UserMembership::latest()->where('status', 'active')->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($data) {
                        return $data->created_at->format('Y-m-d H:i:s');
                    })
                    //user name
                    ->addColumn('user_name', function ($data) {
                        return $data->user->name;
                    })
                    //membership name
                    ->addColumn('membership_name', function ($data) {
                        return $data->membership->name;
                    })

                    ->addColumn('donation_amount', function ($data) {
                        return '$' . number_format($data->donation_amount, 2);
                    })
                    ->addColumn('status', function ($data) {
                        return '<button class="btn btn-sm ' . match ($data->status) {
                            'pending' => 'btn-warning',
                            'active' => 'btn-success',
                            'expired' => 'btn-danger',
                        } . '">' . ucfirst($data->status) . '</button>';
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group">
                           <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                               <i class="fa fa-times"></i>
                           </a>
                       </div>';
                    })
                    ->rawColumns(['status', 'action', 'user_name', 'membership_name'])
                    ->make(true);
            }

            return view('backend.layout.membership_history.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }
    public function delete($id)
    {
        try {
            $data = UserMembership::find($id);
            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Membership not found.'], 404);
            }

            // Delete the user membership
            $data->delete();

            // Delete related payment
            $payment = Payment::where('user_id', $data->user_id)->where('membership_id', $data->membership_id)->first();
            if ($payment) {
                $payment->delete();
            }

            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong! Please try again.'], 500);
        }
    }
}
