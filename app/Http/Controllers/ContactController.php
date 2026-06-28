<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotification;
use App\Mail\CustomerConfirmation;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        $seo = [
            'title' => __('navigation.contact'),
            'description' => __('seo.contact_description'),
            'image' => asset('img/og-default.jpg'),
            'type' => 'website',
        ];

        return view('contact', compact('seo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        // Send emails synchronously per D-16, with try-catch per Research Pitfall 5
        try {
            Mail::to(config('mail.from.address'))
                ->send(new AdminNotification($contact, 'contact'));
            Mail::to($contact->email)
                ->send(new CustomerConfirmation($contact, 'contact'));
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return redirect()->route('contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    }
}
