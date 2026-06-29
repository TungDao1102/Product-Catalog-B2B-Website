<?php

namespace Tests\Feature;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_sitemap_generation_command(): void
    {
        $this->artisan('sitemap:generate')
            ->expectsOutput('Sitemap generated successfully')
            ->assertExitCode(0);
    }

    public function test_sitemap_contains_product_urls(): void
    {
        $this->artisan('sitemap:generate');

        $sitemapContent = file_get_contents(public_path('sitemap.xml'));
        $this->assertStringContainsString('<loc>', $sitemapContent);
        $this->assertStringContainsString('/products/', $sitemapContent);
    }

    public function test_sitemap_contains_category_urls(): void
    {
        $this->artisan('sitemap:generate');

        $sitemapContent = file_get_contents(public_path('sitemap.xml'));
        $this->assertStringContainsString('<loc>', $sitemapContent);
        $this->assertStringContainsString('/categories/', $sitemapContent);
    }

    public function test_sitemap_xml_structure(): void
    {
        $this->artisan('sitemap:generate');

        $sitemapContent = file_get_contents(public_path('sitemap.xml'));
        $this->assertStringContainsString('<urlset', $sitemapContent);
        $this->assertStringContainsString('<url>', $sitemapContent);
        $this->assertStringContainsString('</url>', $sitemapContent);
    }
}
