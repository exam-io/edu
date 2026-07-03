<?php

namespace Modules\ContentProcessing\Infrastructure\Extraction;

use Illuminate\Support\Facades\Storage;
use Modules\ContentProcessing\Application\DTOs\ExtractionResultData;
use Modules\ContentProcessing\Domain\Models\ContentSource;

class PlainTextExtractor
{
    public function extract(ContentSource $source): ExtractionResultData
    {
        $disk = (string) data_get($source->meta, 'disk', 'local');
        $path = (string) data_get($source->meta, 'storage_path', '');

        if ($path === '' || ! Storage::disk($disk)->exists($path)) {
            return new ExtractionResultData(false, null, 'Source file not found.', ['disk' => $disk, 'path' => $path]);
        }

        $contents = Storage::disk($disk)->get($path);

        return new ExtractionResultData(true, trim($contents), null, ['disk' => $disk, 'path' => $path, 'extractor' => 'plain-text']);
    }
}
