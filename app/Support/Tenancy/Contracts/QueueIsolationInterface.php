<?php

namespace App\Support\Tenancy\Contracts;

interface QueueIsolationInterface
{
    public function queue(string $queue): string;
}
