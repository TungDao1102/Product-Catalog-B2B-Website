<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Brands\BrandResource;
use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use Tests\TestCase;

class TranslatableResourcesTest extends TestCase
{
    /**
     * Verify CategoryResource uses Translatable concern and returns correct locales.
     */
    public function test_category_resource_has_translatable_concern(): void
    {
        $this->assertResourceUsesTranslatable(CategoryResource::class);
        $this->assertEquals(['vi', 'en'], CategoryResource::getTranslatableLocales());
    }

    /**
     * Verify BrandResource uses Translatable concern and returns correct locales.
     */
    public function test_brand_resource_has_translatable_concern(): void
    {
        $this->assertResourceUsesTranslatable(BrandResource::class);
        $this->assertEquals(['vi', 'en'], BrandResource::getTranslatableLocales());
    }

    /**
     * Verify ProductResource uses Translatable concern and returns correct locales.
     */
    public function test_product_resource_has_translatable_concern(): void
    {
        $this->assertResourceUsesTranslatable(ProductResource::class);
        $this->assertEquals(['vi', 'en'], ProductResource::getTranslatableLocales());
    }

    /**
     * Verify PostResource uses Translatable concern and returns correct locales.
     */
    public function test_post_resource_has_translatable_concern(): void
    {
        $this->assertResourceUsesTranslatable(PostResource::class);
        $this->assertEquals(['vi', 'en'], PostResource::getTranslatableLocales());
    }

    /**
     * Verify ProjectResource uses Translatable concern and returns correct locales.
     */
    public function test_project_resource_has_translatable_concern(): void
    {
        $this->assertResourceUsesTranslatable(ProjectResource::class);
        $this->assertEquals(['vi', 'en'], ProjectResource::getTranslatableLocales());
    }

    /**
     * Verify all 5 Create pages use Translatable trait.
     */
    public function test_create_pages_have_translatable_trait(): void
    {
        $createPages = [
            CreateCategory::class,
            CreateBrand::class,
            CreateProduct::class,
            CreatePost::class,
            CreateProject::class,
        ];

        foreach ($createPages as $pageClass) {
            $this->assertPageUsesTranslatableTrait($pageClass);
        }
    }

    /**
     * Verify all 5 Edit pages use Translatable trait.
     */
    public function test_edit_pages_have_translatable_trait(): void
    {
        $editPages = [
            EditCategory::class,
            EditBrand::class,
            EditProduct::class,
            EditPost::class,
            EditProject::class,
        ];

        foreach ($editPages as $pageClass) {
            $this->assertPageUsesTranslatableTrait($pageClass);
        }
    }

    /**
     * Assert that a Resource class uses the Translatable concern.
     */
    private function assertResourceUsesTranslatable(string $resourceClass): void
    {
        $reflection = new \ReflectionClass($resourceClass);
        $traits = $reflection->getTraitNames();

        $this->assertContains(
            'LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable',
            $traits,
            "{$resourceClass} does not use the Translatable concern"
        );
    }

    /**
     * Assert that a Create/Edit page class uses the Translatable trait.
     */
    private function assertPageUsesTranslatableTrait(string $pageClass): void
    {
        $reflection = new \ReflectionClass($pageClass);
        $traits = $reflection->getTraitNames();

        // Check for either Create or Edit record Translatable trait
        $hasTrait = in_array(
            'LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable',
            $traits
        ) || in_array(
            'LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable',
            $traits
        );

        $this->assertTrue(
            $hasTrait,
            "{$pageClass} does not use a Translatable trait"
        );
    }
}
