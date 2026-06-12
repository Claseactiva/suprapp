<?php

namespace App\Services;

use App\Models\Detailclient;
use App\Models\Quotationclient;
use App\Models\VehicleModel;
use App\Models\VehicleModelProduct;
use Illuminate\Support\Collection;

class VehicleModelProductService
{
    /**
     * @var array<int, array{id:int, normalized:string, length:int}>|null
     */
    protected $normalizedModels = null;

    public function ensureQuotationVehicleModelId(Quotationclient $quotationclient): ?int
    {
        if (!empty($quotationclient->vehicle_model_id)) {
            return (int) $quotationclient->vehicle_model_id;
        }

        $vehicleModelId = $this->inferVehicleModelId($quotationclient->vehicle);

        if ($vehicleModelId === null) {
            return null;
        }

        Quotationclient::where('id', $quotationclient->id)->update([
            'vehicle_model_id' => $vehicleModelId,
        ]);

        $quotationclient->vehicle_model_id = $vehicleModelId;

        return $vehicleModelId;
    }

    public function inferVehicleModelId(?string $vehicle): ?int
    {
        $normalizedVehicle = $this->normalizeText($vehicle);

        if ($normalizedVehicle === '') {
            return null;
        }

        $needle = ' ' . $normalizedVehicle . ' ';

        foreach ($this->getNormalizedModels() as $model) {
            if (strpos($needle, ' ' . $model['normalized'] . ' ') !== false) {
                return (int) $model['id'];
            }
        }

        return null;
    }

    public function syncDetailclient(Detailclient $detailclient, string $source = 'live'): bool
    {
        $quotationclient = $detailclient->quotationclient()->first();

        if (!$quotationclient) {
            $this->removeDetailclient($detailclient->id);

            return false;
        }

        $vehicleModelId = $this->ensureQuotationVehicleModelId($quotationclient);
        $productName = trim((string) $detailclient->product);
        $productKey = $this->normalizeProductKey($productName);

        if ($vehicleModelId === null || $productKey === '') {
            $this->removeDetailclient($detailclient->id);

            return false;
        }

        VehicleModelProduct::updateOrCreate(
            ['detailclient_id' => $detailclient->id],
            [
                'vehicle_model_id' => $vehicleModelId,
                'quotationclient_id' => $quotationclient->id,
                'product_name' => $productName,
                'product_key' => $productKey,
                'product_code' => $this->normalizeOptionalValue($detailclient->detail),
                'source' => $source,
            ]
        );

        return true;
    }

    public function removeDetailclient(int $detailclientId): void
    {
        VehicleModelProduct::where('detailclient_id', $detailclientId)->delete();
    }

    public function getSuggestionsForQuotation(Quotationclient $quotationclient, int $limit = 25): array
    {
        $vehicleModelId = $this->ensureQuotationVehicleModelId($quotationclient);

        if ($vehicleModelId === null) {
            return [];
        }

        $entries = VehicleModelProduct::where('vehicle_model_id', $vehicleModelId)
            ->orderBy('updated_at', 'desc')
            ->get(['product_name', 'product_code', 'product_key', 'updated_at']);

        $grouped = [];

        foreach ($entries as $entry) {
            if (!isset($grouped[$entry->product_key])) {
                $grouped[$entry->product_key] = [
                    'product_name' => $entry->product_name,
                    'product_code' => $entry->product_code,
                    'product_key' => $entry->product_key,
                    'uses_count' => 0,
                    'last_used_at' => optional($entry->updated_at)->toDateTimeString(),
                ];
            }

            $grouped[$entry->product_key]['uses_count']++;
        }

        $suggestions = collect($grouped)
            ->sort(function (array $a, array $b) {
                if ($a['uses_count'] === $b['uses_count']) {
                    return strcmp((string) ($b['last_used_at'] ?? ''), (string) ($a['last_used_at'] ?? ''));
                }

                return $b['uses_count'] <=> $a['uses_count'];
            })
            ->take($limit)
            ->values()
            ->map(function (array $suggestion) {
                $displayLabel = $suggestion['product_name'];

                if (!empty($suggestion['product_code'])) {
                    $displayLabel .= ' | ' . $suggestion['product_code'];
                }

                if (!empty($suggestion['uses_count'])) {
                    $displayLabel .= ' | ' . $suggestion['uses_count'] . ' uso(s)';
                }

                $suggestion['display_label'] = $displayLabel;

                return $suggestion;
            });

        return $suggestions->all();
    }

    public function syncAllFromHistory(bool $refreshModelId = true): array
    {
        $stats = [
            'quotations_scanned' => 0,
            'model_ids_inferred' => 0,
            'relations_synced' => 0,
            'relations_skipped' => 0,
        ];

        Quotationclient::with(['detailclients:id,quotationclient_id,product,detail'])
            ->select('id', 'vehicle', 'vehicle_model_id')
            ->chunkById(200, function (Collection $quotationclients) use (&$stats, $refreshModelId) {
                foreach ($quotationclients as $quotationclient) {
                    $stats['quotations_scanned']++;
                    $hadModelId = !empty($quotationclient->vehicle_model_id);

                    if ($refreshModelId || !$hadModelId) {
                        $vehicleModelId = $this->ensureQuotationVehicleModelId($quotationclient);

                        if (!$hadModelId && $vehicleModelId !== null) {
                            $stats['model_ids_inferred']++;
                        }
                    }

                    foreach ($quotationclient->detailclients as $detailclient) {
                        if ($this->syncDetailclient($detailclient, 'history')) {
                            $stats['relations_synced']++;
                        } else {
                            $stats['relations_skipped']++;
                        }
                    }
                }
            });

        return $stats;
    }

    protected function getNormalizedModels(): array
    {
        if ($this->normalizedModels !== null) {
            return $this->normalizedModels;
        }

        $models = [];

        foreach (VehicleModel::select('id', 'model')->get() as $vehicleModel) {
            $normalizedModel = $this->normalizeModelName($vehicleModel->model);

            if ($normalizedModel === '') {
                continue;
            }

            $compactModel = str_replace(' ', '', $normalizedModel);
            $length = strlen($compactModel);

            if ($length < 2 || preg_match('/^[0-9]+$/', $compactModel)) {
                continue;
            }

            if (preg_match('/^[A-Z]+$/', $compactModel) && $length < 3) {
                continue;
            }

            $models[] = [
                'id' => (int) $vehicleModel->id,
                'normalized' => $normalizedModel,
                'length' => strlen($normalizedModel),
            ];
        }

        usort($models, function (array $left, array $right) {
            if ($left['length'] === $right['length']) {
                return strcmp($left['normalized'], $right['normalized']);
            }

            return $right['length'] <=> $left['length'];
        });

        $this->normalizedModels = $models;

        return $this->normalizedModels;
    }

    protected function normalizeModelName(?string $value): string
    {
        $normalized = $this->normalizeText($value);

        if ($normalized === 'OTRO' || $normalized === 'GEN') {
            return '';
        }

        return $normalized;
    }

    protected function normalizeProductKey(?string $value): string
    {
        return $this->normalizeText($value);
    }

    protected function normalizeOptionalValue(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    protected function normalizeText(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

        if ($ascii !== false) {
            $value = $ascii;
        }

        $value = strtoupper($value);
        $value = preg_replace('/[^A-Z0-9]+/', ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim((string) $value);
    }
}
