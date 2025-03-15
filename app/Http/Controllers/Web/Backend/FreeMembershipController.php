<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserMembership;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FreeMembershipController extends Controller
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
                           <a href="#" onclick="deleteAlert(' . $data->id . ')" class="text-white btn btn-danger" title="Delete">
                               <i class="fa fa-times"></i>
                           </a>
                       </div>';
                    })
                    ->rawColumns(['status', 'action', 'user_name', 'membership_name'])
                    ->make(true);
            }

            return view('backend.layout.free-membership.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function create()
    {
        $users = User::all();
        $memberships = Membership::where('status', 'active')->get();
        return view('backend.layout.free-membership.create', compact('memberships', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'membership_id' => 'required|exists:memberships,id',
            'status' => 'required|in:pending,active,expired',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,successful,failed',
        ]);


        $membership = Membership::find($request->membership_id);

        //check already membership  membership id
        $userMembership = UserMembership::where('user_id', $request->user_id)->where('membership_id', $request->membership_id)->first();
        if ($userMembership) {
            return redirect()->route('admin.free-membership.index')->with('t-error', 'User already joined this membership');
        }
        $startDate = Carbon::now();
        $endDate = $startDate->copy();
        $endDate->addDays($membership->duration);
        Payment::create([
            'user_id' => $request->user_id,
            'membership_id' => $request->membership_id,
            'amount' => $request->amount,
            'currency' => 'USD',
            'transaction_id' => Str::random(16),
            'status' => $request->payment_status,
        ]);

        UserMembership::create([
            'user_id' => $request->user_id,
            'membership_id' => $request->membership_id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'status' => $request->status,
        ]);

        // Return response or redirect
        return redirect()->route('admin.free-membership.index')
            ->with('success', 'Membership and payment data stored successfully.');
    }

    public function delete($id): ?\Illuminate\Http\JsonResponse
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
