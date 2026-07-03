<?php

namespace Modules\ContentProcessing\Infrastructure\Extraction;

use Modules\ContentProcessing\Application\Contracts\ExtractionPipelineInterface;
use Modules\ContentProcessing\Application\DTOs\ExtractionResultData;
use Modules\ContentProcessing\Domain\Models\ContentSource;

class CompositeExtractionPipeline implements ExtractionPipelineInterface
{
    public function __construct(
        private readonly PlainTextExtractor $plainTextExtractor,
        private readonly PdfTextExtractor $pdfTextExtractor,
    ) {}

    public function extract(ContentSource $source): ExtractionResultData
    {
        if ($source->source_type === 'text') {
            $text = (string) data_get($source->meta, 'raw_text', '');

            return new ExtractionResultData(true, trim($text), null, ['extractor' => 'inline-text']);
        }

        if ($source->mime_type === 'text/plain') {
            return $this->plainTextExtractor->extract($source);
        }

        if ($source->mime_type === 'application/pdf') {
            return $this->pdfTextExtractor->extract($source);
        }

        return new ExtractionResultData(false, null, 'Unsupported source format for extraction.', [
            'source_type' => $source->source_type,
            'mime_type' => $source->mime_type,
        ]);
    }
}
