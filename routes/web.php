<?php

use App\Http\Controllers\PpdbController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/', [PublicPortalController::class, 'home'])->name('home');
Route::get('/profil/tentang-madrasah', [PublicPortalController::class, 'about'])->name('about');
Route::get('/profil/struktur-organisasi', [PublicPortalController::class, 'organization'])->name('organization');
Route::get('/profil/{slug}', [PublicPortalController::class, 'page'])->name('pages.show');
Route::get('/guru', [PublicPortalController::class, 'teachers'])->name('teachers');
Route::get('/ekstrakurikuler', [PublicPortalController::class, 'extracurriculars'])->name('extracurriculars');
Route::get('/kalender-akademik', [PublicPortalController::class, 'calendar'])->name('calendar');
Route::get('/berita', [PublicPortalController::class, 'posts'])->name('posts.index');
Route::get('/berita/{slug}', [PublicPortalController::class, 'post'])->name('posts.show');
Route::get('/pengumuman', [PublicPortalController::class, 'announcements'])->name('announcements.index');
Route::get('/pengumuman/{slug}', [PublicPortalController::class, 'announcement'])->name('announcements.show');
Route::get('/galeri', [PublicPortalController::class, 'gallery'])->name('gallery');
Route::get('/unduhan', [PublicPortalController::class, 'downloads'])->name('downloads');
Route::get('/ppdb', [PpdbController::class, 'create'])->name('ppdb.create');
Route::post('/ppdb', [PpdbController::class, 'store'])->name('ppdb.store');
Route::get('/ppdb/sukses/{registrationNumber}', [PpdbController::class, 'success'])->name('ppdb.success');
