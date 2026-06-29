<?php

namespace Tests\Feature\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use Spatie\Translatable\HasTranslations;
use Tests\TestCase;

class TranslatableModelsTest extends TestCase
{
    /**
     * Verify Category model uses HasTranslations trait with correct translatable fields.
     */
    public function test_category_uses_has_translations(): void
    {
        $this->assertModelUsesTrait(Category::class, HasTranslations::class);

        $model = new Category();
        $this->assertEquals(['name', 'description'], $model->getTranslatableAttributes());
    }

    /**
     * Verify Brand model uses HasTranslations trait with correct translatable fields.
     */
    public function test_brand_uses_has_translations(): void
    {
        $this->assertModelUsesTrait(Brand::class, HasTranslations::class);

        $model = new Brand();
        $this->assertEquals(['name', 'description'], $model->getTranslatableAttributes());
    }

    /**
     * Verify Product model uses HasTranslations trait with correct translatable fields.
     */
    public function test_product_uses_has_translations(): void
    {
        $this->assertModelUsesTrait(Product::class, HasTranslations::class);

        $model = new Product();
        $this->assertEquals(
            ['name', 'description', 'short_description', 'meta_title', 'meta_description'],
            $model->getTranslatableAttributes()
        );
    }

    /**
     * Verify Post model uses HasTranslations trait with correct translatable fields.
     */
    public function test_post_uses_has_translations(): void
    {
        $this->assertModelUsesTrait(Post::class, HasTranslations::class);

        $model = new Post();
        $this->assertEquals(
            ['title', 'content', 'excerpt', 'meta_title', 'meta_description'],
            $model->getTranslatableAttributes()
        );
    }

    /**
     * Verify Project model uses HasTranslations trait with correct translatable fields.
     */
    public function test_project_uses_has_translations(): void
    {
        $this->assertModelUsesTrait(Project::class, HasTranslations::class);

        $model = new Project();
        $this->assertEquals(
            ['title', 'content', 'description', 'meta_title', 'meta_description'],
            $model->getTranslatableAttributes()
        );
    }

    /**
     * Verify Brand model does NOT implement Sitemapable (only Category, Product, Post, Project do).
     */
    public function test_brand_does_not_implement_sitemapable(): void
    {
        $reflection = new \ReflectionClass(Brand::class);
        $interfaces = $reflection->getInterfaceNames();

        $this->assertNotContains(
            'Spatie\Sitemap\Contracts\Sitemapable',
            $interfaces,
            'Brand should not implement Sitemapable'
        );
    }

    /**
     * Assert that a model class uses a specific trait.
     */
    private function assertModelUsesTrait(string $modelClass, string $traitClass): void
    {
        $reflection = new \ReflectionClass($modelClass);
        $traits = $reflection->getTraitNames();

        $this->assertContains(
            $traitClass,
            $traits,
            "{$modelClass} does not use the {$traitClass} trait"
        );
    }
}
