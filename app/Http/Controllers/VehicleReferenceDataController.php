<?php

namespace App\Http\Controllers;

use App\Models\VehicleReferenceData;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleReferenceDataController extends Controller
{
    public function buscar($patente)
    {
        $normalizada = strtoupper(preg_replace('/[\s\-\.]/', '', $patente));

        $registro = VehicleReferenceData::where('ppu_norm', $normalizada)
            ->orderByDesc('periodo')
            ->first();

        if (!$registro) {
            return response()->json(null);
        }

        // Intentar emparejar marca/modelo contra el catálogo real (case-insensitive).
        // Chasis y motor son siempre confiables (texto libre); marca/modelo son
        // "mejor esfuerzo": si no calzan con el catálogo, se dejan como texto sugerido.
        $marcaMatch = VehicleBrand::whereRaw('UPPER(brand) = ?', [strtoupper(trim($registro->marca))])->first();
        $modeloMatch = null;
        $modeloSugerido = null;
        if ($marcaMatch) {
            $modeloMatch = VehicleModel::where('brand_id', $marcaMatch->id)
                ->whereRaw('UPPER(model) = ?', [strtoupper(trim($registro->modelo))])
                ->first();

            if (!$modeloMatch) {
                $modeloSugerido = $this->sugerirModelo($marcaMatch->id, (string) $registro->marca, (string) $registro->modelo);
            }
        }

        return response()->json([
            'periodo' => $registro->periodo,
            'ppu' => $registro->ppu,
            'chasis' => $registro->num_chasis,
            'motor_number' => $registro->num_motor,
            'ano_fabricacion' => $registro->ano_fabricacion,
            'marca_texto' => $registro->marca,
            'modelo_texto' => $registro->modelo,
            'cilindrada_texto' => $this->extraerCilindrada((string) $registro->modelo),
            'marca_match' => $marcaMatch ? ['value' => $marcaMatch->id, 'label' => $marcaMatch->brand] : null,
            'modelo_match' => $modeloMatch ? ['value' => $modeloMatch->id, 'label' => $modeloMatch->model] : null,
            'modelo_sugerido' => $modeloSugerido,
        ]);
    }

    /**
     * El texto de "modelo" del Excel suele traer la cilindrada pegada
     * (ej. "5 2.0 AT", "SPORTAGE 2.2 AUT"). Se extrae aparte para poder
     * ofrecerla como referencia, aunque no exista una columna propia.
     */
    private function extraerCilindrada(string $modeloTexto): ?string
    {
        if (preg_match('/\b(\d+[.,]\d+)\s*L?\b/', strtoupper($modeloTexto), $m)) {
            return str_replace(',', '.', $m[1]);
        }
        return null;
    }

    /**
     * Quita ruido típico de cilindrada/transmisión/tracción del texto de modelo
     * del Excel (ej. "5 2.0 AT" -> "5") para que se parezca más al nombre real
     * del modelo antes de compararlo contra el catálogo.
     */
    private function limpiarTextoModelo(string $texto): string
    {
        $t = strtoupper(trim($texto));
        $t = preg_replace('/\b\d+[.,]\d+\s*L?\b/', ' ', $t);
        $t = preg_replace('/\b(AT|MT|CVT|AUT|AUTOMATICA?|MECANICA?|4X4|4X2|DIESEL|GASOLINA|HIBRIDO|HYBRID|TDI|TSI|DC|SC)\b/', ' ', $t);
        $t = preg_replace('/\s+/', ' ', $t);
        return trim($t);
    }

    /**
     * Busca, entre los modelos de la marca ya identificada, el más parecido al
     * texto de modelo del Excel. Se devuelve solo como sugerencia (con nivel de
     * confianza) para que el usuario la confirme, no se autoselecciona.
     *
     * Se prueban reglas de más a menos estrictas y se corta en la primera que
     * encuentre un único candidato: un simple "contiene" sin límites de palabra
     * es peligroso (ej. el texto limpio "5" aparece dentro de "B2500", "CX5",
     * "MX5" sin ser el mismo modelo), así que eso se evita a propósito.
     */
    private function sugerirModelo(int $marcaId, string $marcaTexto, string $modeloTexto): ?array
    {
        $limpio = $this->limpiarTextoModelo($modeloTexto);
        if ($limpio === '') {
            return null;
        }

        $modelos = VehicleModel::where('brand_id', $marcaId)->get();
        $soloAlfanum = fn (string $s) => strtoupper(preg_replace('/[^A-Z0-9]/i', '', $s));

        // 1) Catálogos que pegan la marca al modelo (MAZDA2, MAZDA3, MAZDA5...):
        //    ¿"MARCA" + texto_limpio calza exacto con el nombre del modelo?
        $concatenado = $soloAlfanum($marcaTexto) . $soloAlfanum($limpio);
        foreach ($modelos as $modelo) {
            if ($soloAlfanum($modelo->model) === $concatenado) {
                return ['value' => $modelo->id, 'label' => $modelo->model, 'confianza' => 95];
            }
        }

        // 2) El texto limpio aparece como palabra completa dentro del modelo
        //    (o al revés), respetando límites de palabra. Solo si tiene largo
        //    suficiente para no ser un dígito suelto que calce por casualidad.
        if (mb_strlen($limpio) >= 2) {
            $patronLimpio = '/(^|[^A-Z0-9])' . preg_quote($limpio, '/') . '($|[^A-Z0-9])/i';
            $candidatosPorPalabra = [];
            foreach ($modelos as $modelo) {
                $candidato = strtoupper(trim($modelo->model));
                if ($candidato === '') {
                    continue;
                }
                $patronCandidato = '/(^|[^A-Z0-9])' . preg_quote($candidato, '/') . '($|[^A-Z0-9])/i';
                if (preg_match($patronLimpio, $candidato) || preg_match($patronCandidato, $limpio)) {
                    $candidatosPorPalabra[] = $modelo;
                }
            }
            // Si hay más de un modelo que matchea por palabra completa, es ambiguo: no adivinar.
            if (count($candidatosPorPalabra) === 1) {
                $modelo = $candidatosPorPalabra[0];
                return ['value' => $modelo->id, 'label' => $modelo->model, 'confianza' => 80];
            }
        }

        // Nota: se descartó a propósito un tercer nivel basado en similitud de
        // texto pura (ej. similar_text). Palabras genéricas compartidas entre
        // modelos distintos de una misma marca (ej. "GRAND" en "GRAND STAREX"
        // vs "GRAND SANTA FE") producían sugerencias falsas con score alto.
        // Mejor no sugerir que sugerir el modelo equivocado.
        return null;
    }
}
