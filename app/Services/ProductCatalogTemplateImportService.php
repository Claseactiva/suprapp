<?php

namespace App\Services;

use App\Models\ProductCatalogTemplate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductCatalogTemplateImportService
{
    public function importFromPath(string $path, bool $fresh = false): array
    {
        return $this->import($path, basename($path), $fresh);
    }

    public function importFromUploadedFile(UploadedFile $file, bool $fresh = true): array
    {
        return $this->import($file->getRealPath(), $file->getClientOriginalName(), $fresh);
    }

    protected function import(string $path, string $label, bool $fresh): array
    {
        if (!is_file($path)) {
            throw new RuntimeException('No se encontro el archivo CSV: ' . $label);
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException('No se pudo abrir el archivo CSV: ' . $label);
        }

        try {
            $header = fgetcsv($handle);

            if ($header === false) {
                throw new RuntimeException('El archivo CSV esta vacio.');
            }

            $normalizedHeader = array_map(function ($column) {
                $column = preg_replace('/^\xEF\xBB\xBF/', '', (string) $column);

                return strtolower(trim((string) $column));
            }, $header);

            $categoryIndex = array_search('categoria', $normalizedHeader, true);
            $nameIndex = array_search('nombre', $normalizedHeader, true);

            if ($categoryIndex === false || $nameIndex === false) {
                throw new RuntimeException('El CSV debe tener las columnas "categoria" y "nombre".');
            }

            $created = 0;
            $existing = 0;
            $skipped = 0;

            DB::transaction(function () use (
                $handle,
                $fresh,
                $categoryIndex,
                $nameIndex,
                &$created,
                &$existing,
                &$skipped
            ) {
                if ($fresh) {
                    ProductCatalogTemplate::query()->delete();
                }

                while (($row = fgetcsv($handle)) !== false) {
                    $categoria = trim((string) ($row[$categoryIndex] ?? ''));
                    $nombre = trim((string) ($row[$nameIndex] ?? ''));

                    if ($categoria === '' && $nombre === '') {
                        $skipped++;
                        continue;
                    }

                    if ($categoria === '' || $nombre === '') {
                        $skipped++;
                        continue;
                    }

                    $template = ProductCatalogTemplate::firstOrCreate([
                        'categoria' => $categoria,
                        'nombre' => $nombre,
                    ]);

                    if ($template->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $existing++;
                    }
                }
            });

            return [
                'archivo' => $label,
                'fresh' => $fresh,
                'creados' => $created,
                'existentes' => $existing,
                'omitidos' => $skipped,
                'total' => ProductCatalogTemplate::count(),
            ];
        } finally {
            fclose($handle);
        }
    }
}
