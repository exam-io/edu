<?php

namespace Modules\QuestionBank\Domain\Events;

class QuestionSetGenerated
{
    public function __construct(
        public readonly int $questionSetId,
        public readonly int $tenantId,
    ) {}
}
