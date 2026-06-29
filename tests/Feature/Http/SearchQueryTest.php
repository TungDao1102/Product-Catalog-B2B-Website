<?php

namespace Tests\Feature\Http;

use Tests\TestCase;

class SearchQueryTest extends TestCase
{
    /**
     * Verify ProductController search uses JSON path syntax for translatable columns.
     */
    public function test_product_controller_search_uses_json_path_syntax(): void
    {
        $controllerPath = app_path('Http/Controllers/ProductController.php');
        $this->assertFileExists($controllerPath);

        $content = file_get_contents($controllerPath);

        // Verify JSON path syntax is used instead of direct column queries
        $this->assertStringContainsString(
            "->where('name->vi'",
            $content,
            'ProductController does not use name->vi JSON path in search'
        );

        $this->assertStringContainsString(
            "->orWhere('name->en'",
            $content,
            'ProductController does not use name->en JSON path in search'
        );

        $this->assertStringContainsString(
            "->orWhere('short_description->vi'",
            $content,
            'ProductController does not use short_description->vi JSON path in search'
        );

        // Verify old-style direct column search is NOT used for translatable fields
        $this->assertStringNotContainsString(
            "->where('name', 'like'",
            $content,
            'ProductController still uses old-style name column search (not JSON path)'
        );
    }

    /**
     * Verify CategoryController search uses JSON path syntax for translatable columns.
     */
    public function test_category_controller_search_uses_json_path_syntax(): void
    {
        $controllerPath = app_path('Http/Controllers/CategoryController.php');
        $this->assertFileExists($controllerPath);

        $content = file_get_contents($controllerPath);

        // Verify JSON path syntax is used
        $this->assertStringContainsString(
            "->where('name->vi'",
            $content,
            'CategoryController does not use name->vi JSON path in search'
        );

        $this->assertStringContainsString(
            "->orWhere('name->en'",
            $content,
            'CategoryController does not use name->en JSON path in search'
        );

        // Verify old-style direct column search is NOT used for translatable fields
        $this->assertStringNotContainsString(
            "->where('name', 'like'",
            $content,
            'CategoryController still uses old-style name column search (not JSON path)'
        );
    }

    /**
     * Verify ProductController sort uses JSON path for name column.
     */
    public function test_product_controller_sort_uses_json_path(): void
    {
        $controllerPath = app_path('Http/Controllers/ProductController.php');
        $content = file_get_contents($controllerPath);

        $this->assertStringContainsString(
            "orderBy('name->vi'",
            $content,
            'ProductController does not use name->vi JSON path in sort'
        );
    }

    /**
     * Verify CategoryController sort uses JSON path for name column.
     */
    public function test_category_controller_sort_uses_json_path(): void
    {
        $controllerPath = app_path('Http/Controllers/CategoryController.php');
        $content = file_get_contents($controllerPath);

        $this->assertStringContainsString(
            "orderBy('name->vi'",
            $content,
            'CategoryController does not use name->vi JSON path in sort'
        );
    }
}
