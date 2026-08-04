<?php

namespace App\Console\Commands;

use App\Services\VehicleEngineRangeMigrationService;
use Illuminate\Console\Command;

class MigrateVehicleEngineRanges extends Command
{
    protected $signature = 'suprapp:migrate-vehicle-engine-ranges {--fresh : Recalcula desde cero las filas de rango ya migradas}';

    protected $description = 'Migra vehicle_engines de una fila por año a una fila por rango de años, y crea motor_specs';

    public function handle(VehicleEngineRangeMigrationService $service): int
    {
        $stats = $service->migrate($this->option('fresh'));

        $this->table(
            ['motor_specs_total', 'ranges_inserted'],
            [[$stats['motor_specs_total'], $stats['ranges_inserted']]]
        );

        $this->info('Migracion completada.');

        return self::SUCCESS;
    }
}
