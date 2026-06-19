<?php

namespace App\Console\Commands;

use App\Models\VehicleModelProduct;
use App\Services\VehicleModelProductService;
use Illuminate\Console\Command;

class SyncVehicleModelProducts extends Command
{
    protected $signature = 'suprapp:sync-model-products {--fresh : Recalcula desde cero la tabla de relaciones}';

    protected $description = 'Sincroniza las relaciones historicas entre modelos de vehiculo y productos cotizados';

    public function handle(VehicleModelProductService $service): int
    {
        if ($this->option('fresh')) {
            VehicleModelProduct::query()->delete();
            $this->info('Tabla vehicle_model_products reiniciada.');
        }

        $stats = $service->syncAllFromHistory(true);

        $this->table(
            ['quotations_scanned', 'model_ids_inferred', 'relations_synced', 'relations_skipped'],
            [[
                $stats['quotations_scanned'],
                $stats['model_ids_inferred'],
                $stats['relations_synced'],
                $stats['relations_skipped'],
            ]]
        );

        $this->info('Sincronizacion completada.');

        return self::SUCCESS;
    }
}
