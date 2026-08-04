<?php

namespace App\Services;

use App\Models\MotorSpec;
use Illuminate\Support\Facades\DB;

class VehicleEngineRangeMigrationService
{
    /**
     * Migra las filas legacy de vehicle_engines (una por año, texto libre en v_engine)
     * al formato nuevo (vehicle_model_id + motor_spec_id + year_from/year_to).
     * Nunca borra ni modifica las filas legacy; solo inserta filas nuevas.
     */
    public function migrate(bool $fresh = false): array
    {
        if ($fresh) {
            DB::table('vehicle_engines')->whereNotNull('year_from')->delete();
        }

        $specIdByText = $this->buildMotorSpecs();
        $grouped = $this->groupLegacyYearsByModelAndSpec($specIdByText);

        $rangesInserted = 0;
        $now = now();

        foreach ($grouped as $modelId => $bySpec) {
            foreach ($bySpec as $specId => $years) {
                sort($years);
                $years = array_values(array_unique($years));

                foreach ($this->buildRanges($years) as [$from, $to]) {
                    DB::table('vehicle_engines')->insert([
                        'vehicle_model_id' => $modelId,
                        'motor_spec_id' => $specId,
                        'year_from' => $from,
                        'year_to' => $to,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $rangesInserted++;
                }
            }
        }

        return [
            'motor_specs_total' => MotorSpec::count(),
            'ranges_inserted' => $rangesInserted,
        ];
    }

    /**
     * Crea (o reutiliza) un motor_spec por cada texto v_engine distinto.
     * Los que matchean "cilindrada combustible" se normalizan a decimal (lo
     * que colapsa duplicados de formato como "2.0" / "2.000" al mismo spec);
     * el resto se migra tal cual como texto libre, sin perder datos.
     */
    private function buildMotorSpecs(): array
    {
        $texts = DB::table('vehicle_engines')
            ->whereNull('year_from')
            ->select('v_engine')
            ->distinct()
            ->pluck('v_engine');

        $specIdByText = [];

        foreach ($texts as $text) {
            if (preg_match('/^(\d+(?:\.\d+)?)\s+(BENCINA|DIESEL|ELECTRICO)$/i', trim($text), $m)) {
                $cilindrada = round((float) $m[1], 2);
                $combustible = strtoupper($m[2]);

                $spec = MotorSpec::firstOrCreate(
                    ['cilindrada' => $cilindrada, 'combustible' => $combustible],
                    ['raw_label' => number_format($cilindrada, 1) . ' ' . $combustible]
                );
            } else {
                $spec = MotorSpec::firstOrCreate(
                    ['cilindrada' => null, 'combustible' => null, 'raw_label' => $text]
                );
            }

            $specIdByText[$text] = $spec->id;
        }

        return $specIdByText;
    }

    /**
     * Agrupa: modelo -> motor_spec_id -> [años] usando las filas legacy
     * (vehicle_engines.year_id -> vehicle_years.v_id/v_year).
     */
    private function groupLegacyYearsByModelAndSpec(array $specIdByText): array
    {
        $rows = DB::table('vehicle_engines')
            ->join('vehicle_years', 'vehicle_years.id', '=', 'vehicle_engines.year_id')
            ->whereNull('vehicle_engines.year_from')
            ->select('vehicle_years.v_id as model_id', 'vehicle_years.v_year as year', 'vehicle_engines.v_engine as text')
            ->get();

        $grouped = [];

        foreach ($rows as $row) {
            $specId = $specIdByText[$row->text] ?? null;
            if ($specId === null) {
                continue;
            }

            $grouped[$row->model_id][$specId][] = (int) $row->year;
        }

        return $grouped;
    }

    /**
     * Parte una lista de años ordenada y unica en rangos contiguos.
     * Ej: [1981,1988,1989,1990] -> [[1981,1981],[1988,1990]]
     */
    private function buildRanges(array $years): array
    {
        $ranges = [];
        $from = $years[0];
        $to = $years[0];

        for ($i = 1; $i < count($years); $i++) {
            if ($years[$i] === $to + 1) {
                $to = $years[$i];
                continue;
            }

            $ranges[] = [$from, $to];
            $from = $years[$i];
            $to = $years[$i];
        }

        $ranges[] = [$from, $to];

        return $ranges;
    }
}
