<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_default_locale(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/en');
    }

    public function test_default_locale_homepage_is_accessible(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
    }
}
