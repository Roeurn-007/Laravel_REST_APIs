<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login.form'));
    }

    public function test_auth_pages_render_successfully(): void
    {
        $this->get(route('login.form'))->assertOk();
        $this->get(route('register.form'))->assertOk();
    }
}
