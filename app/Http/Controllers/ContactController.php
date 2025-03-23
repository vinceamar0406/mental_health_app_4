<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        // Send email to the configured admin email
        Mail::to(env('MAIL_FROM_ADDRESS', 'johnmichaelvincentbaldon040603@gmail.com'))
            ->send(new ContactMail($validatedData));

        // Return success response
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
