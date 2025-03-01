<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\PrivacyAndPolicy;
use App\Models\TermsAndCondition;
use Exception;
use Illuminate\Http\Request;

class TermsAndConditionAndPrivacyPolicyController extends Controller
{
    public function TermsAndCondition()
    {
        $termsAndCondition = TermsAndCondition::first();
        return view('backend.layout.terms_and_condition', compact('termsAndCondition'));
    }

    public function update(Request $request): ?\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'terms' => 'nullable|string',
            'conditions' => 'nullable|string',
        ]);

        $termsAndCondition = TermsAndCondition::firstOrNew();
        $termsAndCondition->terms = $request->terms;
        $termsAndCondition->conditions = $request->conditions;
        try {
            $termsAndCondition->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }
        return redirect()->back()->with('success', 'Terms and condition updated successfully');
    }



    public function privacyPolicy()
    {
        $privacyPolicy = PrivacyAndPolicy::first();
        return view('backend.layout.privacy_policy', compact('privacyPolicy'));
    }
    public function updatePrivacyPolicy(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'privacy' => 'nullable|string',
            'policy' => 'nullable|string',
        ]);

        $PrivacyPolicy = PrivacyAndPolicy::firstOrNew();
        $PrivacyPolicy->privacy = $request->privacy;
        $PrivacyPolicy->policy = $request->policy;
        try {
            $PrivacyPolicy->save();
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update');
        }
        return redirect()->back()->with('success', 'Terms and condition updated successfully');
    }
}
