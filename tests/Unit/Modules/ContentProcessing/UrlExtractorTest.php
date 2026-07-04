<?php

namespace Tests\Unit\Modules\ContentProcessing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Modules\ContentProcessing\Domain\Models\ContentSource;
use Modules\ContentProcessing\Infrastructure\Extraction\UrlExtractor;
use Tests\TestCase;

class UrlExtractorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_extracts_readable_text_from_html_url(): void
    {
        Http::fake([
            'https://example.org/*' => Http::response('<html><head><style>.x{}</style></head><body><h1>Hello</h1><script>alert(1)</script><p>World</p></body></html>', 200),
        ]);

        $source = new ContentSource([
            'source_type' => 'url',
            'source_ref' => 'https://example.org/article',
        ]);

        $result = app(UrlExtractor::class)->extract($source);

        $this->assertTrue($result->success);
        $this->assertNotNull($result->text);
        $this->assertStringContainsString('Hello', (string) $result->text);
        $this->assertStringContainsString('World', (string) $result->text);
    }
}
