<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_en_tetes_de_securite_sont_presents(): void
    {
        foreach (['/', '/blog', '/admin/login', '/sitemap.xml'] as $url) {
            $this->get($url)
                ->assertHeader('X-Content-Type-Options', 'nosniff')
                ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
                ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
                ->assertHeader('Permissions-Policy')
                ->assertHeader('Content-Security-Policy-Report-Only');
        }
    }

    public function test_pas_de_hsts_en_local(): void
    {
        $this->get('/')->assertHeaderMissing('Strict-Transport-Security');
    }
}
