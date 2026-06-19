<?php

namespace App\Http\Controllers;

use App\Models\ProductCatalogTemplate;

class ProductCatalogTemplateController extends Controller
{
    public function suggestions()
    {
        $term = trim((string) request('term', ''));
        $limit = (int) request('limit', 500);

        if ($limit <= 0) {
            $limit = 200;
        }

        $limit = min($limit, 500);

        $templates = ProductCatalogTemplate::query()
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get(['id', 'categoria', 'nombre']);

        $normalizedTerm = $this->normalizeText($term);

        if ($normalizedTerm !== '') {
            $templates = $templates
                ->filter(function (ProductCatalogTemplate $template) use ($normalizedTerm) {
                    $normalizedName = $this->normalizeText($template->nombre);
                    $normalizedCategory = $this->normalizeText($template->categoria);

                    return strpos($normalizedName, $normalizedTerm) !== false
                        || strpos($normalizedCategory, $normalizedTerm) !== false;
                })
                ->sort(function (ProductCatalogTemplate $left, ProductCatalogTemplate $right) use ($normalizedTerm) {
                    $leftPriority = $this->resolvePriority($left, $normalizedTerm);
                    $rightPriority = $this->resolvePriority($right, $normalizedTerm);

                    if ($leftPriority !== $rightPriority) {
                        return $leftPriority <=> $rightPriority;
                    }

                    $categoryComparison = strcmp(
                        $this->normalizeText($left->categoria),
                        $this->normalizeText($right->categoria)
                    );

                    if ($categoryComparison !== 0) {
                        return $categoryComparison;
                    }

                    return strcmp(
                        $this->normalizeText($left->nombre),
                        $this->normalizeText($right->nombre)
                    );
                })
                ->values();
        }

        $totalMatches = $templates->count();

        $suggestions = $templates
            ->take($limit)
            ->map(function (ProductCatalogTemplate $template) {
                return [
                    'id' => (int) $template->id,
                    'categoria' => $template->categoria,
                    'product_name' => $template->nombre,
                    'product_key' => $this->normalizeText($template->nombre),
                    'display_label' => trim($template->nombre . ' | ' . $template->categoria),
                    'source_type' => 'catalog',
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'total' => $totalMatches,
            'suggestions' => $suggestions,
        ]);
    }

    protected function resolvePriority(ProductCatalogTemplate $template, string $normalizedTerm): int
    {
        $normalizedName = $this->normalizeText($template->nombre);
        $normalizedCategory = $this->normalizeText($template->categoria);

        if (strpos($normalizedName, $normalizedTerm) === 0) {
            return 0;
        }

        if (strpos($normalizedCategory, $normalizedTerm) === 0) {
            return 1;
        }

        if (strpos($normalizedName, $normalizedTerm) !== false) {
            return 2;
        }

        if (strpos($normalizedCategory, $normalizedTerm) !== false) {
            return 3;
        }

        return 4;
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
