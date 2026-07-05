<?php

namespace Modules\Reporting\Application\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Reporting\Application\Contracts\ExportServiceInterface;

class ExportService implements ExportServiceInterface
{
    public function export(int $tenantId, int $executionId, array $rows, string $format = 'csv'): string
    {
        $normalizedFormat = strtolower($format) === 'json' ? 'json' : 'csv';
        $directory = 'tenants/' . $tenantId . '/reports';
        $fileName = 'report_' . $executionId . '_' . now()->format('Ymd_His') . '.' . $normalizedFormat;
        $path = $directory . '/' . $fileName;

        Storage::disk('local')->makeDirectory($directory);

        if ($normalizedFormat === 'json') {
            Storage::disk('local')->put($path, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            return $path;
        }

        $header = [];
        if (isset($rows[0]) && is_array($rows[0])) {
            $header = array_keys($rows[0]);
        }

        $csv = '';
        if ($header !== []) {
            $csv .= implode(',', $header) . "\n";
        }

        foreach ($rows as $row) {
            $values = array_map(static function ($value): string {
                $raw = is_scalar($value) || $value === null ? (string) $value : json_encode($value);
                return '"' . str_replace('"', '""', $raw ?? '') . '"';
            }, is_array($row) ? $row : []);

            $csv .= implode(',', $values) . "\n";
        }

        Storage::disk('local')->put($path, $csv);

        return $path;
    }
}
