<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotification;
use App\Mail\CustomerConfirmation;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'message' => 'required|string',
        ]);

        $inquiry = Inquiry::create($validated);

        // Send emails synchronously per D-16, with try-catch per Research Pitfall 5
        try {
            Mail::to(config('mail.from.address'))
                ->send(new AdminNotification($inquiry, 'inquiry'));
            Mail::to($inquiry->email)
                ->send(new CustomerConfirmation($inquiry, 'inquiry'));
        } catch (\Exception $e) {
            Log::error('Failed to send inquiry email: ' . $e->getMessage());
        }

        return redirect()->back()->with('quote_success',
            'Yêu cầu báo giá của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.');
    }
}
