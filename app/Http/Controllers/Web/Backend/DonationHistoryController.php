<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\UserDonation;
use Illuminate\Http\Request;
use App\Models\DonationPayment;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DonationHistoryController extends Controller
{

    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {

        try {
            if ($request->ajax()) {
                $data = DonationPayment::latest()->where('status', 'successful')->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($data) {
                        return $data->created_at->format('Y-m-d H:i:s');
                    })
                    ->addColumn('amount', function ($data) {
                        return '$' . number_format($data->amount, 2);
                    })
                    ->addColumn('status', function ($data) {
                        return '<button class="btn btn-sm ' . match ($data->status) {
                            'pending' => 'btn-warning',
                            'successful' => 'btn-success',
                            'failed' => 'btn-danger',
                        } . '">' . ucfirst($data->status) . '</button>';
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">

                        <a href="#" onclick="deleteAlert(' . $data->id . ')" class="btn btn-danger text-white" title="Delete">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>';
                    })
                    ->rawColumns(['action', 'status', 'created_at', 'amount'])
                    ->make(true);
            }
            return view('backend.layout.donation_history.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('t-error', 'Something went wrong! Please try again.');
        }
    }

    public function delete($id)
    {
        try {
            $data = DonationPayment::find($id);
            if (!$data) {
                return response()->json(['success' => false, 'message' => 'Donation not found.'], 404);
            }

            // Delete the user donation
            $data->delete();

            // Delete related donation payment
            $donation_payment = DonationPayment::where('user_id', $data->user_id)->first();
            if ($donation_payment) {
                $donation_payment->delete();
            }

            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong! Please try again.'], 500);
        }
    }
}
