<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DeliveryTimeController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->effectiveTallerId();

        $deliveryTimes = DeliveryTime::where('user_id', $userId)
            ->orderByDesc('is_default')
            ->orderBy('label')
            ->get();

        return [
            'delivery_times' => $deliveryTimes,
            'default_delivery_time' => $deliveryTimes->firstWhere('is_default', true),
        ];
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $data = $request->validate([
            'label' => ['required', 'string', 'max:255', Rule::unique('delivery_times', 'label')->where('user_id', $userId)],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $label = trim($data['label']);
        $markAsDefault = $request->boolean('is_default') || DeliveryTime::where('user_id', $userId)->count() === 0;

        DB::transaction(function () use ($label, $markAsDefault, $userId) {
            if ($markAsDefault) {
                DeliveryTime::where('user_id', $userId)->update(['is_default' => false]);
            }

            DeliveryTime::create([
                'user_id' => $userId,
                'label' => $label,
                'is_default' => $markAsDefault,
            ]);
        });

        return $this->index();
    }

    public function show($id)
    {
        $deliveryTime = DeliveryTime::findOrFail($id);
        $this->authorizeOwner($deliveryTime->user_id);

        return $deliveryTime;
    }

    public function update(Request $request, $id)
    {
        $deliveryTime = DeliveryTime::findOrFail($id);
        $this->authorizeOwner($deliveryTime->user_id);
        $userId = $deliveryTime->user_id;

        $data = $request->validate([
            'label' => ['required', 'string', 'max:255', Rule::unique('delivery_times', 'label')->where('user_id', $userId)->ignore($deliveryTime->id)],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $label = trim($data['label']);
        $markAsDefault = $request->has('is_default') ? $request->boolean('is_default') : $deliveryTime->is_default;

        DB::transaction(function () use ($deliveryTime, $label, $markAsDefault, $userId) {
            if ($markAsDefault) {
                DeliveryTime::where('user_id', $userId)->update(['is_default' => false]);
            }

            $deliveryTime->update([
                'label' => $label,
                'is_default' => $markAsDefault,
            ]);

            if (!DeliveryTime::where('user_id', $userId)->where('is_default', true)->exists()) {
                $deliveryTime->update(['is_default' => true]);
            }
        });

        return $this->index();
    }

    public function destroy($id)
    {
        $deliveryTime = DeliveryTime::findOrFail($id);
        $this->authorizeOwner($deliveryTime->user_id);
        $userId = $deliveryTime->user_id;
        $wasDefault = $deliveryTime->is_default;

        DB::transaction(function () use ($deliveryTime, $wasDefault, $userId) {
            $deliveryTime->delete();

            if ($wasDefault && !DeliveryTime::where('user_id', $userId)->where('is_default', true)->exists()) {
                $replacement = DeliveryTime::where('user_id', $userId)->orderBy('id')->first();

                if ($replacement) {
                    $replacement->update(['is_default' => true]);
                }
            }
        });

        return $this->index();
    }
}
