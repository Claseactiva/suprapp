<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DeliveryTimeController extends Controller
{
    public function index()
    {
        $deliveryTimes = DeliveryTime::orderByDesc('is_default')
            ->orderBy('label')
            ->get();

        return [
            'delivery_times' => $deliveryTimes,
            'default_delivery_time' => $deliveryTimes->firstWhere('is_default', true),
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255', Rule::unique('delivery_times', 'label')],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $label = trim($data['label']);
        $markAsDefault = $request->boolean('is_default') || DeliveryTime::count() === 0;

        DB::transaction(function () use ($label, $markAsDefault) {
            if ($markAsDefault) {
                DeliveryTime::query()->update(['is_default' => false]);
            }

            DeliveryTime::create([
                'label' => $label,
                'is_default' => $markAsDefault,
            ]);
        });

        return $this->index();
    }

    public function show($id)
    {
        return DeliveryTime::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $deliveryTime = DeliveryTime::findOrFail($id);

        $data = $request->validate([
            'label' => ['required', 'string', 'max:255', Rule::unique('delivery_times', 'label')->ignore($deliveryTime->id)],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $label = trim($data['label']);
        $markAsDefault = $request->has('is_default') ? $request->boolean('is_default') : $deliveryTime->is_default;

        DB::transaction(function () use ($deliveryTime, $label, $markAsDefault) {
            if ($markAsDefault) {
                DeliveryTime::query()->update(['is_default' => false]);
            }

            $deliveryTime->update([
                'label' => $label,
                'is_default' => $markAsDefault,
            ]);

            if (!DeliveryTime::where('is_default', true)->exists()) {
                $deliveryTime->update(['is_default' => true]);
            }
        });

        return $this->index();
    }

    public function destroy($id)
    {
        $deliveryTime = DeliveryTime::findOrFail($id);
        $wasDefault = $deliveryTime->is_default;

        DB::transaction(function () use ($deliveryTime, $wasDefault) {
            $deliveryTime->delete();

            if ($wasDefault && !DeliveryTime::where('is_default', true)->exists()) {
                $replacement = DeliveryTime::orderBy('id')->first();

                if ($replacement) {
                    $replacement->update(['is_default' => true]);
                }
            }
        });

        return $this->index();
    }
}
