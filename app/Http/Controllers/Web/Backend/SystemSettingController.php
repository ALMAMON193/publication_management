<?php

namespace App\Http\Controllers\Web\Backend;

use App\Mail\MembershipExpirationNotification;
use App\Models\UserMembership;
use Carbon\Carbon;
use Exception;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SystemSettingController extends Controller
{

    public function index(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $setting = SystemSetting::latest('id')->first();
        return view('backend.layout.system_setting.index', compact('setting'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'favicon' => 'nullable|mimes:jpeg,png,jpg,gif,svg,ico',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $setting = SystemSetting::firstOrNew();

            // Upload files if present
            foreach (['favicon', 'logo'] as $fileType) {
                if ($request->hasFile($fileType)) {
                    $randomString = Str::random(10);
                    $setting->$fileType = Helper::fileUpload($request->file($fileType), 'system_setting', $randomString);
                }
            }

            $setting->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }
    }


    public function mailSetting(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('backend.layout.system_setting.mailsetting');
    }

    public function mailSettingUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|string',
        ]);
        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";
            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s/',
                '/MAIL_HOST=(.*)\s/',
                '/MAIL_PORT=(.*)\s/',
                '/MAIL_USERNAME=(.*)\s/',
                '/MAIL_PASSWORD=(.*)\s/',
                '/MAIL_ENCRYPTION=(.*)\s/',
                '/MAIL_FROM_ADDRESS=(.*)\s/',
            ], [
                'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
                'MAIL_HOST=' . $request->mail_host . $lineBreak,
                'MAIL_PORT=' . $request->mail_port . $lineBreak,
                'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
                'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }
    }

    public function profileIndex(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {

        return view('backend.layout.system_setting.profile_setting');
    }

    public function profileUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('t-success', 'Profile Update Successfully!');
    }


    public function passwordUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed'
            ],
        ]);

        // Update the user's password
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('t-success', 'Password updated successfully!');
    }

    //palpal setting

    public function PaypalSetting(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('backend.layout.system_setting.paypal_setting');
    }

    public function paypalSettingUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'paypal_client_id' => 'required',
            'paypal_secret' => 'required',
            'paypal_mode' => 'required',
        ]);
        $envContent = File::get(base_path('.env'));
        $lineBreak = "\n";
        $envContent = preg_replace([
            '/PAYPAL_SANDBOX_CLIENT_ID=(.*)\s/',
            '/PAYPAL_SANDBOX_CLIENT_SECRET=(.*)\s/',
            '/PAYPAL_MODE=(.*)\s/',
        ], [
            'PAYPAL_SANDBOX_CLIENT_ID=' . $request->paypal_client_id . $lineBreak,
            'PAYPAL_SANDBOX_CLIENT_SECRET=' . $request->paypal_secret . $lineBreak,
            'PAYPAL_MODE=' . $request->paypal_mode . $lineBreak,
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }
        return redirect()->back()->with('t-success', 'Updated successfully');
    }
    public function StripeSetting(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('backend.layout.system_setting.stripe_setting');
    }

    public function stripeSettingUpdate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'secret' => 'required',
            'key' => 'required',
            'webhook_secret' => 'required',
        ]);
        $envContent = File::get(base_path('.env'));
        $lineBreak = "\n";
        $envContent = preg_replace([
            '/STRIPE_SECRET=(.*)\s/',
            '/STRIPE_KEY=(.*)\s/',
            '/STRIPE_WEBHOOK_SECRET=(.*)\s/',
        ], [
            'STRIPE_SECRET=' . $request->secret . $lineBreak,
            'STRIPE_KEY=' . $request->key . $lineBreak,
            'STRIPE_WEBHOOK_SECRET=' . $request->webhook_secret . $lineBreak,
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }
        return redirect()->back()->with('t-success', 'Updated successfully');
    }

    //admin notify membership


    public function ExpiredMembership(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = UserMembership::with('user')
                    ->whereDate('end_date', '>', Carbon::today())
                    ->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('user_email', function ($data) {
                        return $data->user->email;
                    })
                    ->addColumn('membership_name', function ($data) {
                        return $data->membership->name;
                    })
                    ->addColumn('remaining_days', function ($data) {
                        $endDate = Carbon::parse($data->end_date);
                        $today = Carbon::today();
                        $remainingDays = $today->diffInDays($endDate, false);
                        return $remainingDays > 0 ? $remainingDays . ' days remaining' : 'Expired';
                    })
                    ->addColumn('action', function ($data) {
                        return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                               <button class="btn btn-primary text-white notify-btn" title="Notify" data-id="' . $data->id . '">
                            Notify
                        </button>
                    </div>';
                    })
                    ->rawColumns(['action', 'created_at', 'remaining_days','membership_name'])
                    ->make(true);
            }
            return view('backend.layout.notify_expired_membership');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
    public function notify(Request $request,$id): \Illuminate\Http\JsonResponse
    {
        $membership = UserMembership::with('user')->findOrFail($id);
        $today = Carbon::today();
        // Send the email with the $today variable
        Mail::to($membership->user->email)->send(new MembershipExpirationNotification($membership, $today));
        return response()->json(['success' => true, 'message' => 'Send Message Successfully']);
    }
}
