<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Disable login rate limiter globally for tests; LoginTest restores it where needed.
        \Illuminate\Support\Facades\RateLimiter::for('login', fn () => \Illuminate\Cache\RateLimiting\Limit::none());
    }

    protected function enableLoginRateLimiter(int $max = 10): void
    {
        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) use ($max) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute($max)->by($request->ip());
        });
        \Illuminate\Support\Facades\RateLimiter::clear(sha1('127.0.0.1'));
    }
}
