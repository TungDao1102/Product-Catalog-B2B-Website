<?php

namespace Tests\Feature\Http;

use App\Models\Contact;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_contact_page_renders(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('Gửi liên hệ');
    }

    public function test_contact_form_has_no_subject_field(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertDontSee('name="subject"');
    }

    public function test_contact_form_has_phone_and_company(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertSee('name="phone"');
        $response->assertSee('name="company"');
    }

    public function test_contact_store_validates_required_fields(): void
    {
        $response = $this->post(route('contact.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    public function test_contact_store_creates_record(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0123456789',
            'company' => 'Công ty TNHH ABC',
            'message' => 'Tôi muốn được tư vấn về sản phẩm máy soi chiếu.',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('contacts', [
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'message' => 'Tôi muốn được tư vấn về sản phẩm máy soi chiếu.',
            'is_read' => false,
        ]);
    }

    public function test_contact_store_redirects_with_success(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'message' => 'Tôi muốn được tư vấn về sản phẩm.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');
    }
}
