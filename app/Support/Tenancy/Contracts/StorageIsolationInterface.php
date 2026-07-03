<?php

namespace App\Support\Tenancy\Contracts;

interface StorageIsolationInterface
{
    public function tenantPath(string $path = ''): string;
}
