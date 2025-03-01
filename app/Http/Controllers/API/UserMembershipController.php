<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\UserMembership;
use Dotenv\Validator;
use Illuminate\Http\Request;

class UserMembershipController extends Controller
{
    public function UserList(): \Illuminate\Http\JsonResponse
    {
        $users = UserMembership::all();
        return Helper::jsonResponse(true, 'Membership List Retribe Successfully', 200, $users);
    }
    /*=======================Join Membership ================= */
    public function joinMembership(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'membership_id' => ['required', 'integer'],
        ]);
        //check if membership already exists
        $membership = UserMembership::where('user_id', $request->user_id)
            ->where('membership_id', $request->membership_id)
            ->first();

        if ($membership) {
            return Helper::jsonResponse(false, 'User already joined this membership', 400);
        }

        if ($validator->fails()) {
            return Helper::jsonResponse(false, $validator->errors()->first(), 400);
        }

        $membership = UserMembership::where('user_id', $request->user_id)
            ->where('membership_id', $request->membership_id)
            ->first();

        if ($membership) {
            return Helper::jsonResponse(false, 'User already joined this membership', 400);
        }

        $membership = new UserMembership();
        $membership->user_id = $request->user_id;
        $membership->membership_id = $request->membership_id;
        $membership->save();

        return Helper::jsonResponse(true, 'User joined the membership successfully', 200, $membership);
    }
}
