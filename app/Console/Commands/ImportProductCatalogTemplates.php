<?php

namespace App\Console\Commands;

use App\Models\ProductCatalogTemplate;
use Illuminate\Console\Command;

class ImportProductCatalogTemplates extends Command
{
    protected $signature = 'suprapp:import-product-catalog
        {path=product_catalog_templates.csv : Ruta absoluta o relativa al proyecto}
        {--fresh : Elimina el catalogo actual antes de importar}';

    protected $description = 'Importa el catalogo generico de tipos de productos desde un CSV';

    public function handle(): int
    {
        $pathArgument = (string) $this->argument('path');
        $path = $this->resolvePath($pathArgument);

        if (!is_file($path)) {
            $this->error('No se encontro el archivo CSV: ' . $path);

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            ProductCatalogTemplate::query()->delete();
            $this->info('Tabla product_catalog_templates reiniciada.');
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            $this->error('No se pudo abrir el archivo CSV: ' . $path);

            return self::FAILURE;
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);
            $this->error('El archivo CSV esta vacio.');

            return self::FAILURE;
        }

        $normalizedHeader = array_map(function ($column) {
            $column = preg_replace('/^\xEF\xBB\xBF/', '', (string) $column);

            return strtolower(trim((string) $column));
        }, $header);

        $categoryIndex = array_search('categoria', $normalizedHeader, true);
        $nameIndex = array_search('nombre', $normalizedHeader, true);

        if ($categoryIndex === false || $nameIndex === false) {
            fclose($handle);
            $this->error('El CSV debe tener las columnas "categoria" y "nombre".');

            return self::FAILURE;
        }

        $created = 0;
        $existing = 0;
        $skipped = 0;

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

        fclose($handle);

        $this->table(
            ['archivo', 'creados', 'existentes', 'omitidos'],
            [[
                $path,
                $created,
                $existing,
                $skipped,
            ]]
        );

        $this->info('Importacion del catalogo completada.');

        return self::SUCCESS;
    }

    protected function resolvePath(string $pathArgument): string
    {
        if (preg_match('/^[A-Za-z]:\\\\/', $pathArgument) === 1 || strpos($pathArgument, DIRECTORY_SEPARATOR) === 0) {
            return $pathArgument;
        }

        return base_path($pathArgument);
    }
}
