<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => strtoupper(Str::random(8)),
            'short_description' => fake()->sentence(),
            'description' => '<p>' . fake()->paragraph() . '</p>',
            'technical_specs' => [
                ['attribute_name' => 'Công suất', 'attribute_value' => '200W'],
                ['attribute_name' => 'Điện áp', 'attribute_value' => '220V'],
            ],
            'unit' => fake()->randomElement(['bộ', 'cái', 'bình', 'chiếc']),
            'price' => fake()->randomFloat(0, 100000, 100000000),
            'min_order_qty' => 1,
            'images' => [],
            'brochure' => null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
