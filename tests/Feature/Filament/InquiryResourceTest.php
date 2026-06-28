<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Inquiries\Pages\ViewInquiry;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InquiryResourceTest extends TestCase
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

    public function test_can_list_inquiries(): void
    {
        $product = Product::factory()->create();
        Inquiry::create([
            'product_id' => $product->id,
            'name' => 'Nguyen Van A',
            'email' => 'nguyenvana@example.com',
            'phone' => '0901234567',
            'company' => 'Test Company',
            'quantity' => 10,
            'message' => 'Vui lòng báo giá sản phẩm này',
        ]);

        $response = $this->get('/admin/inquiries');

        $response->assertStatus(200);
        $response->assertSee('Nguyen Van A');
    }

    public function test_can_view_inquiry(): void
    {
        $product = Product::factory()->create();
        $inquiry = Inquiry::create([
            'product_id' => $product->id,
            'name' => 'Tran Thi B',
            'email' => 'tranthib@example.com',
            'phone' => '0909876543',
            'company' => 'Another Company',
            'quantity' => 5,
            'message' => 'Xin báo giá và thông tin giao hàng',
        ]);

        $response = $this->get("/admin/inquiries/{$inquiry->id}");

        $response->assertStatus(200);
        $response->assertSee('Tran Thi B');
    }

    public function test_mark_as_read_on_view(): void
    {
        $product = Product::factory()->create();
        $inquiry = Inquiry::create([
            'product_id' => $product->id,
            'name' => 'Le Van C',
            'email' => 'levanc@example.com',
            'phone' => '0905555555',
            'company' => 'Some Company',
            'quantity' => 2,
            'message' => 'Test message',
            'is_read' => false,
        ]);

        $this->assertFalse($inquiry->fresh()->is_read);

        $this->get("/admin/inquiries/{$inquiry->id}");

        $this->assertTrue($inquiry->fresh()->is_read);
    }

    public function test_can_delete_inquiry(): void
    {
        $product = Product::factory()->create();
        $inquiry = Inquiry::create([
            'product_id' => $product->id,
            'name' => 'To Be Deleted',
            'email' => 'delete@example.com',
            'phone' => '0900000000',
            'company' => 'Delete Co',
            'quantity' => 1,
            'message' => 'Delete me',
        ]);

        Livewire::test(ViewInquiry::class, ['record' => $inquiry->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('inquiries', ['id' => $inquiry->id]);
    }
}
