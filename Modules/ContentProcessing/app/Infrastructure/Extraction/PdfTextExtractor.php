<?php

namespace Modules\ContentProcessing\Infrastructure\Extraction;

use Illuminate\Support\Facades\Storage;
use Modules\ContentProcessing\Application\DTOs\ExtractionResultData;
use Modules\ContentProcessing\Domain\Models\ContentSource;
use Smalot\PdfParser\Parser;

class PdfTextExtractor
{
    public function __construct(
        private readonly Parser $parser,
    ) {}

    public function extract(ContentSource $source): ExtractionResultData
    {
        $disk = (string) data_get($source->meta, 'disk', 'local');
        $path = (string) data_get($source->meta, 'storage_path', '');

        if ($path === '' || ! Storage::disk($disk)->exists($path)) {
            return new ExtractionResultData(false, null, 'Source PDF file not found.', ['disk' => $disk, 'path' => $path]);
        }

        try {
            $binary = Storage::disk($disk)->get($path);
            $pdf = $this->parser->parseContent($binary);
            $text = trim($pdf->getText());

            if ($text === '') {
                return new ExtractionResultData(false, null, 'PDF text extraction returned empty output.', [
                    'extractor' => 'smalot/pdfparser',
                    'disk' => $disk,
                    'path' => $path,
                ]);
            }

            return new ExtractionResultData(true, $text, null, [
                'extractor' => 'smalot/pdfparser',
                'disk' => $disk,
                'path' => $path,
            ]);
        } catch (\Throwable $exception) {
            return new ExtractionResultData(false, null, 'Failed to parse PDF content.', [
                'extractor' => 'smalot/pdfparser',
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
