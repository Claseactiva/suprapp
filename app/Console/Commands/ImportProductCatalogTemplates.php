<?php

namespace App\Console\Commands;

use App\Services\ProductCatalogTemplateImportService;
use Illuminate\Console\Command;
use RuntimeException;

class ImportProductCatalogTemplates extends Command
{
    protected $signature = 'suprapp:import-product-catalog
        {path=product_catalog_templates.csv : Ruta absoluta o relativa al proyecto}
        {--fresh : Elimina el catalogo actual antes de importar}';

    protected $description = 'Importa el catalogo generico de tipos de productos desde un CSV';

    public function handle(ProductCatalogTemplateImportService $importService): int
    {
        $pathArgument = (string) $this->argument('path');
        $path = $this->resolvePath($pathArgument);

        try {
            $result = $importService->importFromPath($path, (bool) $this->option('fresh'));

            if ($result['fresh']) {
                $this->info('Tabla product_catalog_templates reiniciada.');
            }

            $this->table(
                ['archivo', 'creados', 'existentes', 'omitidos'],
                [[
                    $path,
                    $result['creados'],
                    $result['existentes'],
                    $result['omitidos'],
                ]]
            );

            $this->info('Importacion del catalogo completada.');

            return self::SUCCESS;
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    protected function resolvePath(string $pathArgument): string
    {
        if (preg_match('/^[A-Za-z]:\\\\/', $pathArgument) === 1 || strpos($pathArgument, DIRECTORY_SEPARATOR) === 0) {
            return $pathArgument;
        }

        return base_path($pathArgument);
    }
}
