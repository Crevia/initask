<?php

namespace Tests\Feature;

use App\Models\DefinedUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test url shorten
     */
    public function test_shorten_a_url(): void
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);
        $response = $this->post('urls/minify', [
            'url' => 'https://example.com'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect("urls");
        $this->assertDatabaseHas('defined_urls', [
            'original' => 'https://example.com',
        ]);
    }

    public function test_same_hash_for_duplicate_url(): void
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);
        $response1 = $this->post('urls/minify', [
            'url' => 'https://example.com'
        ]);
        $response2 = $this->post('urls/minify', [
            'url' => 'https://example.com'
        ]);

        $response1->assertStatus(302);
        $response1->assertRedirect("urls");
        $response2->assertStatus(302);
        $response2->assertRedirect("urls");

        $this->assertDatabaseCount('defined_urls', 1);
        $this->assertDatabaseHas('defined_urls', [
            'original' => 'https://example.com',
        ]);
    }

    public function test_redirects_to_the_original_url(): void
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([], 200),
        ]);
        $response = $this->post('urls/minify', [
            'url' => 'https://example.com'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("urls");
        $shorUrls = $this->get('urls');



        $shorUrls->assertStatus(200);
        $rawShortUrl = DefinedUrl::first();

        $redirect_response = $this->get($rawShortUrl->full_url);
        $redirect_response->assertRedirect($rawShortUrl->original);
    }


    public function test_url_is_safe(): void
    {
        Http::fake([
            'https://safebrowsing.googleapis.com/*' => Http::response([
                'matches' => [
                    [
                        'threatType' => 'MALWARE',
                        'platformType' => 'ANY_PLATFORM',
                        'threatEntryType' => 'URL',
                        'threat' => ['url' => 'https://fake-url.com']
                    ]
                ]
            ], 200),
        ]);
        $response = $this->post('urls/minify', [
            'url' => 'https://fake-url.com'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect();
        $this->assertDatabaseHas('defined_urls', [
            'original' => 'https://fake-url.com',
        ]);
    }

    public function test_url_is_valid(): void
    {

        $response = $this->post('urls/minify', [
            'url' => 'fake-url-com'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['url']);
    }
}
