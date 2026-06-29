<?php

namespace Tests\Feature\Http;

use Tests\TestCase;

class SeoMetaTagsTest extends TestCase
{
    /**
     * Verify the language-switcher Blade component exists.
     */
    public function test_language_switcher_component_exists(): void
    {
        $this->assertFileExists(
            resource_path('views/partials/language-switcher.blade.php'),
            'Language switcher component not found'
        );
    }

    /**
     * Verify the language-switcher component renders locale switch links for VI and EN.
     */
    public function test_language_switcher_renders_locale_links(): void
    {
        $content = file_get_contents(resource_path('views/partials/language-switcher.blade.php'));

        // Should reference both locales
        $this->assertStringContainsString(
            "'vi'",
            $content,
            'Language switcher is missing vi locale reference'
        );

        $this->assertStringContainsString(
            "'en'",
            $content,
            'Language switcher is missing en locale reference'
        );
    }

    /**
     * Verify the layout blade has OG meta tags.
     */
    public function test_layout_has_og_meta_tags(): void
    {
        $content = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $ogTags = [
            'og:title',
            'og:description',
            'og:image',
            'og:type',
            'og:url',
            'og:locale',
        ];

        foreach ($ogTags as $tag) {
            $this->assertStringContainsString(
                $tag,
                $content,
                "Layout is missing OG meta tag: {$tag}"
            );
        }
    }

    /**
     * Verify the layout has hreflang alternate language links.
     */
    public function test_layout_has_hreflang_links(): void
    {
        $content = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString(
            'hreflang="vi"',
            $content,
            'Layout is missing hreflang="vi" link'
        );

        $this->assertStringContainsString(
            'hreflang="en"',
            $content,
            'Layout is missing hreflang="en" link'
        );

        $this->assertStringContainsString(
            'hreflang="x-default"',
            $content,
            'Layout is missing hreflang="x-default" link'
        );
    }

    /**
     * Verify the navbar view includes the language switcher.
     */
    public function test_navbar_includes_language_switcher(): void
    {
        $content = file_get_contents(resource_path('views/partials/navbar.blade.php'));

        $this->assertStringContainsString(
            'language-switcher',
            $content,
            'Navbar does not include language-switcher component'
        );
    }

    /**
     * Verify the navbar uses __() calls for navigation labels.
     */
    public function test_navbar_uses_translatable_labels(): void
    {
        $content = file_get_contents(resource_path('views/partials/navbar.blade.php'));

        $keys = [
            "__('navigation.home')",
            "__('navigation.products')",
            "__('navigation.categories')",
            "__('navigation.posts')",
            "__('navigation.projects')",
            "__('navigation.contact')",
        ];

        foreach ($keys as $key) {
            $this->assertStringContainsString(
                $key,
                $content,
                "Navbar is missing translatable call: {$key}"
            );
        }
    }

    /**
     * Verify the layout consumes $seo data in OG meta tags.
     */
    public function test_layout_consumes_seo_data(): void
    {
        $content = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString(
            '$seo',
            $content,
            'Layout does not reference $seo data'
        );

        // Verify @yield fallbacks reference $seo
        $this->assertStringContainsString(
            "\$seo['title']",
            $content,
            'Layout does not use $seo title fallback'
        );

        $this->assertStringContainsString(
            "\$seo['description']",
            $content,
            'Layout does not use $seo description fallback'
        );
    }

    /**
     * Verify controllers pass $seo data to their views.
     */
    public function test_controllers_pass_seo_data(): void
    {
        $controllers = [
            'HomeController.php',
            'ProductController.php',
            'CategoryController.php',
            'PostController.php',
            'ProjectController.php',
            'ContactController.php',
        ];

        foreach ($controllers as $controllerFile) {
            $path = app_path("Http/Controllers/{$controllerFile}");
            $this->assertFileExists($path, "Controller file missing: {$controllerFile}");

            $content = file_get_contents($path);

            $this->assertStringContainsString(
                '$seo',
                $content,
                "{$controllerFile} does not define \$seo data"
            );

            $this->assertStringContainsString(
                "'title'",
                $content,
                "{$controllerFile} \$seo is missing 'title' key"
            );

            $this->assertStringContainsString(
                "'description'",
                $content,
                "{$controllerFile} \$seo is missing 'description' key"
            );

            $this->assertStringContainsString(
                "'image'",
                $content,
                "{$controllerFile} \$seo is missing 'image' key"
            );

            $this->assertStringContainsString(
                "'type'",
                $content,
                "{$controllerFile} \$seo is missing 'type' key"
            );
        }
    }

    /**
     * Verify the <html lang> attribute in the layout dynamically reflects the current locale.
     */
    public function test_layout_html_lang_is_dynamic(): void
    {
        $content = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString(
            'app()->getLocale()',
            $content,
            'Layout html lang attribute does not use dynamic locale'
        );

        $this->assertStringContainsString(
            'vi_VN',
            $content,
            'Layout html lang attribute is missing vi_VN locale mapping'
        );

        $this->assertStringContainsString(
            'en_US',
            $content,
            'Layout html lang attribute is missing en_US locale mapping'
        );
    }

    /**
     * Verify the robots.txt route returns expected content structure.
     */
    public function test_robots_txt_exists_in_routes(): void
    {
        $routesPath = base_path('routes/web.php');
        $content = file_get_contents($routesPath);

        $this->assertStringContainsString(
            '/robots.txt',
            $content,
            'Routes file does not define /robots.txt route'
        );

        $this->assertStringContainsString(
            'Allow: /',
            $content,
            'robots.txt route does not contain Allow: /'
        );

        $this->assertStringContainsString(
            'Sitemap:',
            $content,
            'robots.txt route does not reference Sitemap URL'
        );
    }

    /**
     * Verify the lang files exist for both locales with expected structure.
     */
    public function test_lang_files_exist_for_both_locales(): void
    {
        $locales = ['vi', 'en'];
        $files = ['navigation', 'common', 'products', 'validation', 'pagination', 'seo', 'home'];

        foreach ($locales as $locale) {
            foreach ($files as $file) {
                $path = lang_path("{$locale}/{$file}.php");
                $this->assertFileExists(
                    $path,
                    "Lang file missing: {$path}"
                );
            }
        }
    }
}
