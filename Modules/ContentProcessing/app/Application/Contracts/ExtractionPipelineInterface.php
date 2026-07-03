<?php

namespace Modules\ContentProcessing\Application\Contracts;

use Modules\ContentProcessing\Application\DTOs\ExtractionResultData;
use Modules\ContentProcessing\Domain\Models\ContentSource;

interface ExtractionPipelineInterface
{
    public function extract(ContentSource $source): ExtractionResultData;
}
