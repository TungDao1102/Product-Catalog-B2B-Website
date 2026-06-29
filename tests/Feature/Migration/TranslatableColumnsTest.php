<?php

namespace Tests\Feature\Migration;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TranslatableColumnsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Verify categories table has JSON columns for translatable fields.
     *
     * @requires extension pdo_mysql
     */
    public function test_categories_table_has_json_columns(): void
    {
        $columns = [
            'name' => 'json',
            'description' => 'json',
        ];

        foreach ($columns as $column => $expectedType) {
            $this->assertTrue(
                Schema::hasColumn('categories', $column),
                "categories table is missing column: {$column}"
            );
        }
    }

    /**
     * Verify brands table has JSON columns for translatable fields.
     *
     * @requires extension pdo_mysql
     */
    public function test_brands_table_has_json_columns(): void
    {
        $columns = [
            'name' => 'json',
            'description' => 'json',
        ];

        foreach ($columns as $column => $expectedType) {
            $this->assertTrue(
                Schema::hasColumn('brands', $column),
                "brands table is missing column: {$column}"
            );
        }
    }

    /**
     * Verify products table has JSON columns for translatable fields.
     *
     * @requires extension pdo_mysql
     */
    public function test_products_table_has_json_columns(): void
    {
        $columns = [
            'name' => 'json',
            'description' => 'json',
            'short_description' => 'json',
            'meta_title' => 'json',
            'meta_description' => 'json',
        ];

        foreach ($columns as $column => $expectedType) {
            $this->assertTrue(
                Schema::hasColumn('products', $column),
                "products table is missing column: {$column}"
            );
        }
    }

    /**
     * Verify posts table has JSON columns for translatable fields + meta columns.
     *
     * @requires extension pdo_mysql
     */
    public function test_posts_table_has_json_columns(): void
    {
        $columns = [
            'title' => 'json',
            'content' => 'json',
            'excerpt' => 'json',
            'meta_title' => 'json',
            'meta_description' => 'json',
        ];

        foreach ($columns as $column => $expectedType) {
            $this->assertTrue(
                Schema::hasColumn('posts', $column),
                "posts table is missing column: {$column}"
            );
        }
    }

    /**
     * Verify projects table has JSON columns for translatable fields + meta columns.
     *
     * @requires extension pdo_mysql
     */
    public function test_projects_table_has_json_columns(): void
    {
        $columns = [
            'title' => 'json',
            'content' => 'json',
            'description' => 'json',
            'meta_title' => 'json',
            'meta_description' => 'json',
        ];

        foreach ($columns as $column => $expectedType) {
            $this->assertTrue(
                Schema::hasColumn('projects', $column),
                "projects table is missing column: {$column}"
            );
        }
    }
}
