<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Publication;
use App\Models\UserMembership;
use App\Http\Controllers\Controller;

class PublicationController extends Controller
{
    public function Publications(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return Helper::jsonResponse(false, 'User Not Found', 404);
        }
        //user payment is membership
        $payment = Payment::where('user_id', $user->id)->where('status', 'successful')->first();

        if (!$payment) {
            return Helper::jsonResponse(false, 'You have not a successful payment', 202);
        }
        $check_endTime = UserMembership::where('user_id', $user->id)
            ->where('end_date', '<', now())
            ->latest('end_date')
            ->first();

        if ($check_endTime) {
            return Helper::jsonResponse(false, 'Your membership has expired', 202);
        }


        if ($check_endTime) {
            return Helper::jsonResponse(false, 'Your membership has expired', 202);
        }
        //spastic user get a membership with payment
        $user_membership = UserMembership::where('user_id', $user->id)->where('status', 'active')->first();
        if (!$user_membership) {
            return Helper::jsonResponse(false, 'User Not Found', 404);
        }
        $data = Publication::all()->groupBy('category.name');
        $data = [];
        $categories = Category::all();
        foreach ($categories as $category) {
            $data[$category->name] = $category->publications->map(function ($publication) {
                return $publication;
            });
        }
        return Helper::jsonResponse(true, 'Publication Data Fetch Successfully', 200, $data);
    }

    public function PublicationDetails($publication_id): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return Helper::jsonResponse(false, 'User Not Found', 404);
        }
        //user payment is membership
        $payment = Payment::where('user_id', $user->id)->where('status', 'successful')->first();

        if (!$payment) {
            return Helper::jsonResponse(false, 'You have not a successful payment', 404);
        }
        //spasic user get a membership with payment
        $user_membership = UserMembership::where('user_id', $user->id)->where('status', 'active')->first();
        if (!$user_membership) {
            return Helper::jsonResponse(false, 'User Not Found', 404);
        }
        $publication = Publication::find($publication_id);
        $publication->description = strip_tags($publication->description);
        return Helper::jsonResponse(true, 'Publication Data Fetch Successfully', 200, $publication);
    }
}
