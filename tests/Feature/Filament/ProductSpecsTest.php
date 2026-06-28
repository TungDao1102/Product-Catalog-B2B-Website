<?php

namespace Tests\Feature\Filament;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSpecsTest extends TestCase
{
    use RefreshDatabase;

    public function test_technical_specs_formats_as_array_of_objects(): void
    {
        $specs = [
            ['attribute_name' => 'Công suất', 'attribute_value' => '200W'],
            ['attribute_name' => 'Điện áp', 'attribute_value' => '220V'],
        ];

        $product = Product::factory()->create([
            'technical_specs' => $specs,
        ]);

        $retrieved = $product->fresh()->technical_specs;
        $this->assertIsArray($retrieved);
        $this->assertCount(2, $retrieved);
        $this->assertEquals('Công suất', $retrieved[0]['attribute_name']);
        $this->assertEquals('200W', $retrieved[0]['attribute_value']);
        $this->assertEquals('Điện áp', $retrieved[1]['attribute_name']);
        $this->assertEquals('220V', $retrieved[1]['attribute_value']);
    }

    public function test_technical_specs_can_be_null(): void
    {
        $product = Product::factory()->create([
            'technical_specs' => null,
        ]);

        $this->assertNull($product->fresh()->technical_specs);
    }

    public function test_technical_specs_every_entry_has_attribute_name_and_value(): void
    {
        $specs = [
            ['attribute_name' => 'Kích thước', 'attribute_value' => '800x600mm'],
            ['attribute_name' => 'Trọng lượng', 'attribute_value' => '15kg'],
        ];

        $product = Product::factory()->create([
            'technical_specs' => $specs,
        ]);

        $retrieved = $product->fresh()->technical_specs;
        $this->assertIsArray($retrieved);

        foreach ($retrieved as $spec) {
            $this->assertArrayHasKey('attribute_name', $spec);
            $this->assertArrayHasKey('attribute_value', $spec);
        }
    }
}
