<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function ContactList(Request $request): \Illuminate\Http\JsonResponse
    {
        $contacts = Contact::all();
        return Helper::jsonResponse(true, 'Contact list retrieved successfully', 200, $contacts);
    }
    public function Contact(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|max:20|unique:contacts,email',
            'subject' => 'required|max:120',
            'fname' => 'required',
            'lname' => 'required',
            'message' => 'nullable'
        ]);
        try {
            $contactData = [
                'email' => $request->email,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'subject' => $request->subject,
                'message' => $request->message,
            ];
            Contact::create($contactData);
            return Helper::jsonResponse(true, 'Contact created successfully', 200, $contactData);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, $e->getMessage(), 500);
        }
    }
}
