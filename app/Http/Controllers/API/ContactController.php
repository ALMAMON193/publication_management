<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Helpers\Helper;
use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
            'email' => 'required|email',
            'subject' => 'required',
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

            // Store contact details in DB
            Contact::create($contactData);

            // Send email to admin
            Mail::to('mamunkhan14108@gmail.com')->send(new ContactMail($contactData));

            return Helper::jsonResponse(true, 'Contact created successfully', 200, $contactData);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, $e->getMessage(), 500);
        }
    }
}
