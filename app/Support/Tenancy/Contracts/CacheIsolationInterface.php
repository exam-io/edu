<?php

namespace App\Support\Tenancy\Contracts;

interface CacheIsolationInterface
{
    public function key(string $key): string;
}
