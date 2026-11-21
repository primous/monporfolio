<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/** Chemins visés par les « Google Dorks » : jamais servis, journalisés, IP bloquée en cas de scan. */
class AntiDorkingTest extends TestCase
{
    use RefreshDatabase;

    private const ROBOT = ['REMOTE_ADDR' => '203.0.113.7'];

    public static function cheminsSensibles(): array
    {
        return array_map(fn ($c) => [$c], [
            '/.env', '/.env.backup', '/.git/HEAD', '/.git/config', '/.aws/credentials', '/.DS_Store',
            '/storage/logs/laravel.log', '/vendor/autoload.php', '/config/app.php', '/database/database.sqlite',
            '/backup.sql', '/dump.sql.gz', '/site.zip', '/index.php.bak', '/id_rsa', '/composer.json',
            '/wp-admin/', '/wp-login.php', '/xmlrpc.php', '/phpmyadmin', '/actuator/env',
            '/phpinfo.php', '/info.php', '/server-status', '/telescope', '/horizon', '/_ignition/health-check', '/up',
        ]);
    }

    /** @dataProvider cheminsSensibles */
    public function test_les_chemins_sensibles_renvoient_404_sans_contenu(string $chemin): void
    {
        $reponse = $this->withServerVariables(self::ROBOT)->get($chemin);

        $reponse->assertNotFound()->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
        $this->assertStringNotContainsString('APP_KEY', $reponse->getContent());
        $this->assertStringNotContainsString('DB_PASSWORD', $reponse->getContent());
    }

    public function test_chaque_tentative_est_journalisee(): void
    {
        Log::spy();
        $this->withServerVariables(self::ROBOT)->get('/.env');
        Log::shouldHaveReceived('channel')->with('securite');
    }

    public function test_plus_de_10_tentatives_en_5_minutes_bloquent_l_ip(): void
    {
        for ($i = 0; $i < 11; $i++) {
            $this->withServerVariables(self::ROBOT)->get('/.env')->assertNotFound();
        }

        // Le site entier lui est fermé…
        $this->withServerVariables(self::ROBOT)->get('/')->assertForbidden();
        // …mais pas aux autres visiteurs.
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.9'])->get('/')->assertOk();
    }

    public function test_les_pages_normales_ne_sont_pas_considerees_comme_un_scan(): void
    {
        foreach (['/', '/services', '/blog', '/sitemap.xml', '/robots.txt', '/mentions-legales'] as $page) {
            $this->withServerVariables(self::ROBOT)->get($page)->assertOk();
        }
    }

    public function test_robots_txt_contient_les_leurres_mais_jamais_la_vraie_adresse_admin(): void
    {
        $robots = $this->get('/robots.txt')->getContent();

        foreach (['/admin/', '/login/', '/.env', '/.git/', '/backup/', '/storage/', '/config/'] as $leurre) {
            $this->assertStringContainsString("Disallow: {$leurre}", $robots);
        }
        $admin = config('site.admin_path');
        if ($admin !== 'admin') {
            $this->assertStringNotContainsString($admin, $robots);
        }
    }

    public function test_l_admin_n_est_jamais_indexable(): void
    {
        $this->get('/'.config('site.admin_path').'/login')->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
        $this->get('/')->assertHeaderMissing('X-Robots-Tag');
    }
}
