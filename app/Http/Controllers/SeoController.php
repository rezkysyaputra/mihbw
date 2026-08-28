<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('about'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => route('organization'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('teachers'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('extracurriculars'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('calendar'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => route('posts.index'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('announcements.index'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('gallery'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('downloads'), 'lastmod' => now(), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('ppdb.create'), 'lastmod' => now(), 'changefreq' => 'weekly', 'priority' => '0.9'],
        ]);

        $pages = Page::where('status', 'published')
            ->whereNotIn('slug', [
                'profil-sekolah',
                'sejarah-singkat',
                'fasilitas',
                'struktur-organisasi',
                'sambutan-kepala-madrasah',
            ])
            ->get()
            ->map(fn (Page $page) => [
                'loc' => route('pages.show', $page->slug),
                'lastmod' => $page->updated_at,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ]);

        $posts = Post::where('status', 'published')->latest('published_at')->get()->map(fn (Post $post) => [
            'loc' => route('posts.show', $post->slug),
            'lastmod' => $post->updated_at,
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ]);

        $announcements = Announcement::where('status', 'published')->latest('published_at')->get()->map(fn (Announcement $announcement) => [
            'loc' => route('announcements.show', $announcement->slug),
            'lastmod' => $announcement->updated_at,
            'changefreq' => 'monthly',
            'priority' => '0.7',
        ]);

        return response()
            ->view('seo.sitemap', ['urls' => $urls->merge($pages)->merge($posts)->merge($announcements)])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /ppdb/sukses/',
            'Allow: /',
            'Sitemap: '.url('/sitemap.xml'),
            '',
        ]);

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
