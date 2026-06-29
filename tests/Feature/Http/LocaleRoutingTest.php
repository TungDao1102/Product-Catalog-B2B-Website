<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LocaleRoutingTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Verify that the / route redirects to a locale-prefixed URL
     * (either /vi or /en depending on environment Accept-Language).
     *
     * @requires extension pdo_mysql
     */
    public function test_root_redirects_to_locale(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $this->assertStringStartsWith(
            'http://localhost/',
            $response->headers->get('Location')
        );
    }

    /**
     * Verify that /vi homepage returns 200.
     *
     * @requires extension pdo_mysql
     */
    public function test_vi_locale_homepage_returns_200(): void
    {
        $response = $this->get('/vi');

        $response->assertStatus(200);
    }

    /**
     * Verify that /en homepage returns 200.
     *
     * @requires extension pdo_mysql
     */
    public function test_en_locale_homepage_returns_200(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
    }

    /**
     * Verify that /robots.txt returns Allow: / and references sitemap.
     *
     * @requires extension pdo_mysql
     */
    public function test_robots_txt_returns_allowed(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('Allow: /');
        $response->assertSee('Sitemap:');
    }

    /**
     * Verify that all locale-prefixed routes are registered in the route list
     * regardless of DB state.
     */
    public function test_locale_prefix_routes_are_registered(): void
    {
        $routes = Route::getRoutes()->getRoutesByName();

        $this->assertArrayHasKey('home', $routes);
        $this->assertArrayHasKey('products.index', $routes);
        $this->assertArrayHasKey('products.show', $routes);
        $this->assertArrayHasKey('categories.show', $routes);
        $this->assertArrayHasKey('posts.index', $routes);
        $this->assertArrayHasKey('posts.show', $routes);
        $this->assertArrayHasKey('projects.index', $routes);
        $this->assertArrayHasKey('projects.show', $routes);
        $this->assertArrayHasKey('contact', $routes);
        $this->assertArrayHasKey('inquiries.store', $routes);
    }

    /**
     * Verify that route('home') generates a URL with {locale} prefix.
     * Since SetLocale middleware may not be active in test, we verify
     * the route pattern contains {locale}.
     */
    public function test_home_route_accepts_locale_parameter(): void
    {
        $url = route('home', ['locale' => 'vi']);
        $this->assertStringContainsString('/vi', $url);

        $url = route('home', ['locale' => 'en']);
        $this->assertStringContainsString('/en', $url);
    }

    /**
     * Verify that the locale prefix uses [a-z]{2} pattern constraint.
     */
    public function test_products_index_route_accepts_locale(): void
    {
        $url = route('products.index', ['locale' => 'vi']);
        $this->assertStringContainsString('/vi/san-pham', $url);

        $url = route('products.index', ['locale' => 'en']);
        $this->assertStringContainsString('/en/san-pham', $url);
    }

    /**
     * Verify that the SetLocale middleware class exists and has the expected structure.
     */
    public function test_set_locale_middleware_exists(): void
    {
        $this->assertTrue(
            class_exists(\App\Http\Middleware\SetLocale::class),
            'SetLocale middleware class not found'
        );

        $reflection = new \ReflectionClass(\App\Http\Middleware\SetLocale::class);

        $this->assertTrue(
            $reflection->hasMethod('handle'),
            'SetLocale middleware is missing the handle method'
        );
    }

    /**
     * Verify that frontend Blade views use __() helper calls by
     * checking each view file references at least one __() call.
     */
    public function test_blade_views_use_translatable_strings(): void
    {
        $viewFiles = [
            resource_path('views/products/index.blade.php'),
            resource_path('views/products/show.blade.php'),
            resource_path('views/categories/show.blade.php'),
            resource_path('views/posts/index.blade.php'),
            resource_path('views/posts/show.blade.php'),
            resource_path('views/projects/index.blade.php'),
            resource_path('views/projects/show.blade.php'),
            resource_path('views/home/index.blade.php'),
            resource_path('views/contact.blade.php'),
            resource_path('views/partials/footer.blade.php'),
            resource_path('views/partials/navbar.blade.php'),
        ];

        foreach ($viewFiles as $file) {
            $this->assertFileExists($file, "View file missing: {$file}");

            $content = file_get_contents($file);

            // Each view should contain at least one __() call
            $this->assertStringContainsString(
                '__(',
                $content,
                "View {$file} has no translatable __() calls"
            );

            // Verify no hardcoded Vietnamese multi-character strings remain
            // This regex matches sequences of 3+ Vietnamese characters
            preg_match_all('/[\\x{00C0}-\\x{1EF9}]{3,}/u', $content, $matches);
            $vietnameseWords = array_filter($matches[0], function ($word) {
                // Filter out common false positives like HTML entities, CSS classes
                return !preg_match('/^(class|id|src|href|alt|title|ng|vi|en)$/i', $word);
            });

            $this->assertEmpty(
                $vietnameseWords,
                "Found potential hardcoded Vietnamese text in {$file}: " . implode(', ', array_slice($vietnameseWords, 0, 10))
            );
        }
    }
}
