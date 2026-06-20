<?php

namespace App\Http\Controllers;

use App\Models\ProductCatalogTemplate;
use App\Services\ProductCatalogTemplateImportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class ProductCatalogTemplateController extends Controller
{
    public function index()
    {
        $categoria = trim((string) request('categoria', ''));
        $nombre = trim((string) request('nombre', ''));

        $templates = ProductCatalogTemplate::query()
            ->when($categoria !== '', function ($query) use ($categoria) {
                return $query->where('categoria', 'like', '%' . $categoria . '%');
            })
            ->when($nombre !== '', function ($query) use ($nombre) {
                return $query->where('nombre', 'like', '%' . $nombre . '%');
            })
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'total' => $templates->count(),
            'templates' => $templates,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateTemplate($request);

        $template = ProductCatalogTemplate::create($data);

        return response()->json($template);
    }

    public function update(Request $request, $id)
    {
        $template = ProductCatalogTemplate::findOrFail($id);
        $data = $this->validateTemplate($request, $template->id);

        $template->update($data);

        return response()->json($template->fresh());
    }

    public function destroy($id)
    {
        $template = ProductCatalogTemplate::findOrFail($id);
        $template->delete();

        return response()->json($template);
    }

    public function import(Request $request, ProductCatalogTemplateImportService $importService)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt',
            'fresh' => 'nullable|boolean',
        ]);

        try {
            $result = $importService->importFromUploadedFile(
                $request->file('import_file'),
                $request->boolean('fresh', true)
            );

            return response()->json($result);
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

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

    protected function validateTemplate(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'categoria' => [
                'required',
                'string',
                'max:191',
            ],
            'nombre' => [
                'required',
                'string',
                'max:191',
                Rule::unique('product_catalog_templates')
                    ->ignore($ignoreId)
                    ->where(function ($query) use ($request) {
                        return $query->where('categoria', trim((string) $request->input('categoria')));
                    }),
            ],
        ]);

        return [
            'categoria' => trim((string) $data['categoria']),
            'nombre' => trim((string) $data['nombre']),
        ];
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
