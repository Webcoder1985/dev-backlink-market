<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactForm;

class ContactUsFormController extends Controller
{

    public function ContactUsForm(Request $request) {
        // Form validation
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
         ]);
        /* $request['subject'] = "Contact Form Submission";
        $request['name'] = $request->get('name');
        $request['email'] = $request->get('email');
        $request['user_query'] = $request->get('message');
        */
        Mail::to(config('app.admin_email'))->send(new ContactForm($request->all()));
        return back()->with('success', "Thank you. We'll get back to you within 48 hours.");
    }
}
