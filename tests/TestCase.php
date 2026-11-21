<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Monolog\Handler\NullHandler;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Les faux scans des tests ne doivent pas remplir le vrai journal de sécurité.
        config(['logging.channels.securite' => ['driver' => 'monolog', 'handler' => NullHandler::class]]);
    }
}
