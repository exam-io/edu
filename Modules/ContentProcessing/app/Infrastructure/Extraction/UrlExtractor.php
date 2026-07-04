<?php

namespace Modules\ContentProcessing\Infrastructure\Extraction;

use Illuminate\Support\Facades\Http;
use Modules\ContentProcessing\Application\DTOs\ExtractionResultData;
use Modules\ContentProcessing\Domain\Models\ContentSource;

class UrlExtractor
{
    public function extract(ContentSource $source): ExtractionResultData
    {
        $url = trim((string) $source->source_ref);

        if ($url === '' || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return new ExtractionResultData(false, null, 'Invalid source URL.', [
                'source_type' => $source->source_type,
                'source_ref' => $source->source_ref,
            ]);
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if (! in_array($scheme, ['http', 'https'], true)) {
            return new ExtractionResultData(false, null, 'Only http/https URLs are supported.', [
                'url' => $url,
                'scheme' => $scheme,
            ]);
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'User-Agent' => 'EduOS-ContentExtractor/1.0',
                    'Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);

            if (! $response->successful()) {
                return new ExtractionResultData(false, null, 'Failed to fetch URL content.', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
            }

            $html = (string) $response->body();
            $text = $this->extractReadableText($html);

            if ($text === '') {
                return new ExtractionResultData(false, null, 'URL extraction produced empty text.', [
                    'url' => $url,
                    'extractor' => 'url-html',
                ]);
            }

            return new ExtractionResultData(true, $text, null, [
                'url' => $url,
                'extractor' => 'url-html',
                'content_length' => strlen($html),
            ]);
        } catch (\Throwable $exception) {
            return new ExtractionResultData(false, null, 'URL extraction failed.', [
                'url' => $url,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function extractReadableText(string $html): string
    {
        $withoutScript = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', ' ', $html) ?? $html;
        $withoutStyle = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', ' ', $withoutScript) ?? $withoutScript;
        $withoutNoscript = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is', ' ', $withoutStyle) ?? $withoutStyle;

        $decoded = html_entity_decode(strip_tags($withoutNoscript), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = preg_replace('/\s+/u', ' ', $decoded) ?? $decoded;

        return trim($normalized);
    }
}
