<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml with alternate locale URLs';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Static pages — home
        $sitemap->add(Url::create('/vi')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->addAlternate('vi', '/vi')
            ->addAlternate('en', '/en'));

        $sitemap->add(Url::create('/en')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->addAlternate('vi', '/vi')
            ->addAlternate('en', '/en'));

        // Static pages — contact
        $sitemap->add(Url::create('/vi/lien-he')
            ->setPriority(0.5)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->addAlternate('vi', '/vi/lien-he')
            ->addAlternate('en', '/en/contact'));

        $sitemap->add(Url::create('/en/contact')
            ->setPriority(0.5)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->addAlternate('vi', '/vi/lien-he')
            ->addAlternate('en', '/en/contact'));

        // Dynamic models
        Product::all()->each(fn (Product $product) => $sitemap->add($product->toSitemapTag()));
        Category::all()->each(fn (Category $category) => $sitemap->add($category->toSitemapTag()));
        Post::all()->each(fn (Post $post) => $sitemap->add($post->toSitemapTag()));
        Project::all()->each(fn (Project $project) => $sitemap->add($project->toSitemapTag()));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at ' . public_path('sitemap.xml'));
    }
}
