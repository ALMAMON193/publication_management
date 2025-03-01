<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'system_name' => 'nullable',
            'footer_description' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'favicon' => 'nullable|mimes:jpeg,png,jpg,gif,svg,ico',
            'copyright' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $setting = SystemSetting::firstOrNew();
            $setting->fill($request->only(['system_name', 'footer_description', 'copyright']));

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
}
