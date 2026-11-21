<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Avec ADMIN_PATH personnalisée : la nouvelle adresse répond, /admin devient un leurre (404 journalisé). */
class AdminPathPersonnaliseTest extends TestCase
{
    use RefreshDatabase;

    private const CHEMIN = 'gestion-test-7f3a';

    protected function setUp(): void
    {
        putenv('ADMIN_PATH='.self::CHEMIN);
        $_ENV['ADMIN_PATH'] = $_SERVER['ADMIN_PATH'] = self::CHEMIN;
        parent::setUp();
    }

    protected function tearDown(): void
    {
        putenv('ADMIN_PATH');
        unset($_ENV['ADMIN_PATH'], $_SERVER['ADMIN_PATH']);
        parent::tearDown();
    }

    public function test_la_nouvelle_adresse_remplace_admin(): void
    {
        $this->assertSame(self::CHEMIN, config('site.admin_path'));

        $this->get('/'.self::CHEMIN.'/login')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
        $this->get('/'.self::CHEMIN)->assertRedirect('/'.self::CHEMIN.'/login');

        foreach (['/admin', '/admin/login', '/admin/demandes'] as $leurre) {
            $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])->get($leurre)->assertNotFound();
        }
        $this->assertStringNotContainsString(self::CHEMIN, $this->get('/robots.txt')->getContent());
    }
}
