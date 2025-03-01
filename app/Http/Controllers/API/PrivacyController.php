<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\PrivacyAndPolicy;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PrivacyController extends Controller
{
    public function getPrivacyPolicy(): \Illuminate\Http\JsonResponse
    {
        try {
            $privacyPolicy = PrivacyAndPolicy::first();
            if (!$privacyPolicy) {
                return Helper::jsonResponse(false, 'Privacy Policy not found.', 404, []);
            }
            return Helper::jsonResponse(true, 'Privacy Policy retrieved successfully.', 200, [
                'privacy' => $privacyPolicy->privacy,
                'policy' => $privacyPolicy->policy,
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong.', 500);
        }
    }
}
