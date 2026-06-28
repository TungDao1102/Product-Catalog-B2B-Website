<?php

namespace Tests\Feature\Http;

use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class InquiryFormTest extends TestCase
{
    use DatabaseMigrations;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->product = Product::firstOrFail();
    }

    public function test_inquiry_store_validates_required_fields(): void
    {
        $response = $this->post(route('inquiries.store'), []);

        $response->assertSessionHasErrors(['product_id', 'name', 'email', 'message']);
    }

    public function test_inquiry_store_creates_record(): void
    {
        $response = $this->post(route('inquiries.store'), [
            'product_id' => $this->product->id,
            'name' => 'Nguyễn Văn B',
            'email' => 'nguyenvanb@example.com',
            'phone' => '0987654321',
            'company' => 'Công ty TNHH XYZ',
            'quantity' => 10,
            'message' => 'Tôi muốn báo giá sản phẩm này với số lượng lớn.',
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('inquiries', [
            'product_id' => $this->product->id,
            'name' => 'Nguyễn Văn B',
            'email' => 'nguyenvanb@example.com',
            'quantity' => 10,
            'is_read' => false,
        ]);
    }

    public function test_inquiry_store_redirects_back_with_success(): void
    {
        $response = $this->from(route('products.show', $this->product->slug))
            ->post(route('inquiries.store'), [
                'product_id' => $this->product->id,
                'name' => 'Nguyễn Văn B',
                'email' => 'nguyenvanb@example.com',
                'message' => 'Tôi muốn báo giá.',
            ]);

        $response->assertRedirect(route('products.show', $this->product->slug));
        $response->assertSessionHas('quote_success');
    }
}
