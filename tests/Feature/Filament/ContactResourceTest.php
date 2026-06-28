<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Contacts\Pages\ViewContact;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContactResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();

        $this->actingAs(User::factory()->create([
            'email' => 'admin@example.com',
        ]));
    }

    public function test_can_list_contacts(): void
    {
        Contact::create([
            'name' => 'Nguyen Van A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0901234567',
            'company' => 'Test Company',
            'message' => 'Xin liên hệ tôi',
        ]);

        $response = $this->get('/admin/contacts');

        $response->assertStatus(200);
        $response->assertSee('Nguyen Van A');
    }

    public function test_can_view_contact(): void
    {
        $contact = Contact::create([
            'name' => 'Tran Thi B',
            'email' => 'tranthib@example.com',
            'phone' => '0909876543',
            'company' => 'Another Company',
            'message' => 'Vui lòng gọi lại cho tôi',
        ]);

        $response = $this->get("/admin/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertSee('Tran Thi B');
    }

    public function test_mark_as_read_on_view(): void
    {
        $contact = Contact::create([
            'name' => 'Le Van C',
            'email' => 'levanc@example.com',
            'phone' => '0905555555',
            'company' => 'Some Company',
            'message' => 'Test message',
            'is_read' => false,
        ]);

        $this->assertFalse($contact->fresh()->is_read);

        $this->get("/admin/contacts/{$contact->id}");

        $this->assertTrue($contact->fresh()->is_read);
    }

    public function test_can_delete_contact(): void
    {
        $contact = Contact::create([
            'name' => 'To Be Deleted',
            'email' => 'delete@example.com',
            'phone' => '0900000000',
            'company' => 'Delete Co',
            'message' => 'Delete me',
        ]);

        Livewire::test(ViewContact::class, ['record' => $contact->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
