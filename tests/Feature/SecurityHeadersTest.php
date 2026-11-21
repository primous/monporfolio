<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_en_tetes_de_securite_sont_presents_partout(): void
    {
        // Pages, admin, sitemap, et même les 404 renvoyées aux robots.
        foreach (['/', '/blog', '/admin/login', '/sitemap.xml', '/.env', '/page-inexistante'] as $url) {
            $this->get($url)
                ->assertHeader('X-Content-Type-Options', 'nosniff')
                ->assertHeader('X-Frame-Options', 'DENY')
                ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
                ->assertHeader('Permissions-Policy')
                ->assertHeader('Cross-Origin-Opener-Policy', 'same-origin')
                ->assertHeaderMissing('X-Powered-By');
        }
    }

    public function test_csp_appliquee_sur_le_site_public_et_signalee_sur_l_admin(): void
    {
        $csp = $this->get('/')->assertHeaderMissing('Content-Security-Policy-Report-Only')->headers->get('Content-Security-Policy');

        foreach (["default-src 'self'", "object-src 'none'", "frame-ancestors 'none'", "frame-src 'none'", "base-uri 'self'", "form-action 'self'"] as $directive) {
            $this->assertStringContainsString($directive, $csp);
        }
        $this->assertStringNotContainsString('*', $csp, 'Aucun joker dans la CSP');

        $this->get('/admin/login')->assertHeader('Content-Security-Policy-Report-Only');
    }

    public function test_pas_de_hsts_en_local(): void
    {
        $this->get('/')->assertHeaderMissing('Strict-Transport-Security');
    }
}
