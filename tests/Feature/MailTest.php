<?php

namespace Tests\Feature;

use App\Mail\AdminNotification;
use App\Mail\CustomerConfirmation;
use App\Models\Contact;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MailTest extends TestCase
{
    use DatabaseMigrations;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->product = Product::firstOrFail();
    }

    public function test_admin_notification_mailable_for_inquiry(): void
    {
        $inquiry = Inquiry::create([
            'product_id' => $this->product->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'Test message',
        ]);

        $mailable = new AdminNotification($inquiry, 'inquiry');

        $mailable->assertSeeInHtml('Yêu cầu báo giá mới');
        $mailable->assertSeeInHtml($inquiry->name);
    }

    public function test_admin_notification_mailable_for_contact(): void
    {
        $contact = Contact::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'Test message',
        ]);

        $mailable = new AdminNotification($contact, 'contact');

        $mailable->assertSeeInHtml('Liên hệ mới');
        $mailable->assertSeeInHtml($contact->name);
    }

    public function test_customer_confirmation_mailable_for_inquiry(): void
    {
        $inquiry = Inquiry::create([
            'product_id' => $this->product->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'Test message',
        ]);

        $mailable = new CustomerConfirmation($inquiry, 'inquiry');

        $mailable->assertSeeInHtml('Cảm ơn bạn đã liên hệ!');
        $mailable->assertSeeInHtml($inquiry->name);
    }

    public function test_customer_confirmation_mailable_for_contact(): void
    {
        $contact = Contact::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'message' => 'Test message',
        ]);

        $mailable = new CustomerConfirmation($contact, 'contact');

        $mailable->assertSeeInHtml('Cảm ơn bạn đã liên hệ!');
        $mailable->assertSeeInHtml($contact->name);
    }

    public function test_mailable_renders_view_without_errors(): void
    {
        $inquiry = Inquiry::create([
            'product_id' => $this->product->id,
            'name' => 'Render Test',
            'email' => 'render@example.com',
            'message' => 'Testing render method',
        ]);

        $adminMailable = new AdminNotification($inquiry, 'inquiry');
        $customerMailable = new CustomerConfirmation($inquiry, 'inquiry');

        $adminMailable->render();
        $customerMailable->render();

        $this->assertTrue(true);
    }
}
