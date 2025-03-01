<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\PrivacyAndPolicy;
use App\Models\TermsAndCondition;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class TermsAndConditionController extends Controller
{
    public function getTermsAndConditions(): \Illuminate\Http\JsonResponse
    {
        try {
            $termsAndConditions = TermsAndCondition::first();

            if (!$termsAndConditions) {
                return Helper::jsonResponse(false, 'Terms and Conditions not found.', 404, []);
            }
            return Helper::jsonResponse(true, 'Terms and Conditions retrieved successfully.', 200, [
                'terms' => $termsAndConditions->terms,
                'conditions' => $termsAndConditions->conditions,
            ]);
        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return Helper::jsonResponse(false, 'Something went wrong.', 500);
        }
    }
}
