<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Helper: convert a string column to JSON with data migration
        // Strategy: add _new JSON column, copy data as {vi: ...}, drop old, rename new

        // ========== CATEGORIES ==========
        Schema::table('categories', function ($table) {
            $table->json('name_new')->nullable()->after('name');
            $table->json('description_new')->nullable()->after('description');
        });
        DB::table('categories')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('categories')->where('id', $row->id)->update([
                    'name_new' => json_encode(['vi' => $row->name]),
                    'description_new' => json_encode(['vi' => $row->description]),
                ]);
            }
        });
        Schema::table('categories', function ($table) {
            $table->dropColumn(['name', 'description']);
        });
        Schema::table('categories', function ($table) {
            $table->renameColumn('name_new', 'name');
            $table->renameColumn('description_new', 'description');
        });
        DB::statement('ALTER TABLE categories MODIFY name JSON NOT NULL');

        // ========== BRANDS ==========
        Schema::table('brands', function ($table) {
            $table->json('name_new')->nullable()->after('name');
            $table->json('description_new')->nullable()->after('description');
        });
        DB::table('brands')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('brands')->where('id', $row->id)->update([
                    'name_new' => json_encode(['vi' => $row->name]),
                    'description_new' => json_encode(['vi' => $row->description]),
                ]);
            }
        });
        Schema::table('brands', function ($table) {
            $table->dropColumn(['name', 'description']);
        });
        Schema::table('brands', function ($table) {
            $table->renameColumn('name_new', 'name');
            $table->renameColumn('description_new', 'description');
        });
        DB::statement('ALTER TABLE brands MODIFY name JSON NOT NULL');

        // ========== PRODUCTS ==========
        Schema::table('products', function ($table) {
            $table->json('name_new')->nullable()->after('name');
            $table->json('description_new')->nullable()->after('description');
            $table->json('short_description_new')->nullable()->after('short_description');
            $table->json('meta_title_new')->nullable()->after('meta_title');
            $table->json('meta_description_new')->nullable()->after('meta_description');
        });
        DB::table('products')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('products')->where('id', $row->id)->update([
                    'name_new' => json_encode(['vi' => $row->name]),
                    'description_new' => json_encode(['vi' => $row->description]),
                    'short_description_new' => json_encode(['vi' => $row->short_description]),
                    'meta_title_new' => json_encode(['vi' => $row->meta_title]),
                    'meta_description_new' => json_encode(['vi' => $row->meta_description]),
                ]);
            }
        });
        Schema::table('products', function ($table) {
            $table->dropColumn(['name', 'description', 'short_description', 'meta_title', 'meta_description']);
        });
        Schema::table('products', function ($table) {
            $table->renameColumn('name_new', 'name');
            $table->renameColumn('description_new', 'description');
            $table->renameColumn('short_description_new', 'short_description');
            $table->renameColumn('meta_title_new', 'meta_title');
            $table->renameColumn('meta_description_new', 'meta_description');
        });
        DB::statement('ALTER TABLE products MODIFY name JSON NOT NULL');

        // ========== POSTS ==========
        Schema::table('posts', function ($table) {
            $table->json('title_new')->nullable()->after('title');
            $table->json('content_new')->nullable()->after('content');
            $table->json('excerpt_new')->nullable()->after('excerpt');
            // Add new meta columns directly
            $table->json('meta_title')->nullable()->after('excerpt');
            $table->json('meta_description')->nullable()->after('meta_title');
        });
        DB::table('posts')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('posts')->where('id', $row->id)->update([
                    'title_new' => json_encode(['vi' => $row->title]),
                    'content_new' => json_encode(['vi' => $row->content]),
                    'excerpt_new' => json_encode(['vi' => $row->excerpt]),
                    'meta_title' => json_encode(['vi' => '']),
                    'meta_description' => json_encode(['vi' => '']),
                ]);
            }
        });
        Schema::table('posts', function ($table) {
            $table->dropColumn(['title', 'content', 'excerpt']);
        });
        Schema::table('posts', function ($table) {
            $table->renameColumn('title_new', 'title');
            $table->renameColumn('content_new', 'content');
            $table->renameColumn('excerpt_new', 'excerpt');
        });
        DB::statement('ALTER TABLE posts MODIFY title JSON NOT NULL');

        // ========== PROJECTS ==========
        Schema::table('projects', function ($table) {
            $table->json('title_new')->nullable()->after('title');
            $table->json('content_new')->nullable()->after('content');
            $table->json('description_new')->nullable()->after('description');
            // Add new meta columns directly
            $table->json('meta_title')->nullable()->after('description');
            $table->json('meta_description')->nullable()->after('meta_title');
        });
        DB::table('projects')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('projects')->where('id', $row->id)->update([
                    'title_new' => json_encode(['vi' => $row->title]),
                    'content_new' => json_encode(['vi' => $row->content]),
                    'description_new' => json_encode(['vi' => $row->description]),
                    'meta_title' => json_encode(['vi' => '']),
                    'meta_description' => json_encode(['vi' => '']),
                ]);
            }
        });
        Schema::table('projects', function ($table) {
            $table->dropColumn(['title', 'content', 'description']);
        });
        Schema::table('projects', function ($table) {
            $table->renameColumn('title_new', 'title');
            $table->renameColumn('content_new', 'content');
            $table->renameColumn('description_new', 'description');
        });
        DB::statement('ALTER TABLE projects MODIFY title JSON NOT NULL');
    }

    public function down(): void
    {
        // ========== CATEGORIES ==========
        Schema::table('categories', function ($table) {
            $table->string('name_old')->nullable()->after('name');
            $table->text('description_old')->nullable()->after('description');
        });
        DB::table('categories')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $name = json_decode($row->name, true)['vi'] ?? '';
                $desc = json_decode($row->description, true)['vi'] ?? '';
                DB::table('categories')->where('id', $row->id)->update([
                    'name_old' => $name,
                    'description_old' => $desc,
                ]);
            }
        });
        Schema::table('categories', function ($table) {
            $table->dropColumn(['name', 'description']);
        });
        Schema::table('categories', function ($table) {
            $table->renameColumn('name_old', 'name');
            $table->renameColumn('description_old', 'description');
        });
        DB::statement('ALTER TABLE categories MODIFY name VARCHAR(255) NOT NULL');

        // ========== BRANDS ==========
        Schema::table('brands', function ($table) {
            $table->string('name_old')->nullable()->after('name');
            $table->text('description_old')->nullable()->after('description');
        });
        DB::table('brands')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $name = json_decode($row->name, true)['vi'] ?? '';
                $desc = json_decode($row->description, true)['vi'] ?? '';
                DB::table('brands')->where('id', $row->id)->update([
                    'name_old' => $name,
                    'description_old' => $desc,
                ]);
            }
        });
        Schema::table('brands', function ($table) {
            $table->dropColumn(['name', 'description']);
        });
        Schema::table('brands', function ($table) {
            $table->renameColumn('name_old', 'name');
            $table->renameColumn('description_old', 'description');
        });
        DB::statement('ALTER TABLE brands MODIFY name VARCHAR(255) NOT NULL');

        // ========== PRODUCTS ==========
        Schema::table('products', function ($table) {
            $table->string('name_old')->nullable()->after('name');
            $table->text('description_old')->nullable()->after('description');
            $table->text('short_description_old')->nullable()->after('short_description');
            $table->string('meta_title_old')->nullable()->after('meta_title');
            $table->text('meta_description_old')->nullable()->after('meta_description');
        });
        DB::table('products')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('products')->where('id', $row->id)->update([
                    'name_old' => json_decode($row->name, true)['vi'] ?? '',
                    'description_old' => json_decode($row->description, true)['vi'] ?? '',
                    'short_description_old' => json_decode($row->short_description, true)['vi'] ?? '',
                    'meta_title_old' => json_decode($row->meta_title, true)['vi'] ?? '',
                    'meta_description_old' => json_decode($row->meta_description, true)['vi'] ?? '',
                ]);
            }
        });
        Schema::table('products', function ($table) {
            $table->dropColumn(['name', 'description', 'short_description', 'meta_title', 'meta_description']);
        });
        Schema::table('products', function ($table) {
            $table->renameColumn('name_old', 'name');
            $table->renameColumn('description_old', 'description');
            $table->renameColumn('short_description_old', 'short_description');
            $table->renameColumn('meta_title_old', 'meta_title');
            $table->renameColumn('meta_description_old', 'meta_description');
        });
        DB::statement('ALTER TABLE products MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE products MODIFY meta_title VARCHAR(255) NULL');

        // ========== POSTS ==========
        Schema::table('posts', function ($table) {
            $table->string('title_old')->nullable()->after('title');
            $table->longText('content_old')->nullable()->after('content');
            $table->text('excerpt_old')->nullable()->after('excerpt');
        });
        DB::table('posts')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('posts')->where('id', $row->id)->update([
                    'title_old' => json_decode($row->title, true)['vi'] ?? '',
                    'content_old' => json_decode($row->content, true)['vi'] ?? '',
                    'excerpt_old' => json_decode($row->excerpt, true)['vi'] ?? '',
                ]);
            }
        });
        Schema::table('posts', function ($table) {
            $table->dropColumn(['title', 'content', 'excerpt', 'meta_title', 'meta_description']);
        });
        Schema::table('posts', function ($table) {
            $table->renameColumn('title_old', 'title');
            $table->renameColumn('content_old', 'content');
            $table->renameColumn('excerpt_old', 'excerpt');
        });
        DB::statement('ALTER TABLE posts MODIFY title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE posts MODIFY content LONGTEXT NULL');

        // ========== PROJECTS ==========
        Schema::table('projects', function ($table) {
            $table->string('title_old')->nullable()->after('title');
            $table->longText('content_old')->nullable()->after('content');
            $table->text('description_old')->nullable()->after('description');
        });
        DB::table('projects')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('projects')->where('id', $row->id)->update([
                    'title_old' => json_decode($row->title, true)['vi'] ?? '',
                    'content_old' => json_decode($row->content, true)['vi'] ?? '',
                    'description_old' => json_decode($row->description, true)['vi'] ?? '',
                ]);
            }
        });
        Schema::table('projects', function ($table) {
            $table->dropColumn(['title', 'content', 'description', 'meta_title', 'meta_description']);
        });
        Schema::table('projects', function ($table) {
            $table->renameColumn('title_old', 'title');
            $table->renameColumn('content_old', 'content');
            $table->renameColumn('description_old', 'description');
        });
        DB::statement('ALTER TABLE projects MODIFY title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE projects MODIFY content LONGTEXT NULL');
    }
};
