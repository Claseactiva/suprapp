<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function inventories() {
        return $this->hasMany('App\Models\Inventory');
    }

    public function productSales() {
        return $this->hasMany('App\Models\ProductSale');
    }

    public function scopeName($query)
    {
        $keyword = request('name');
        if ($query) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }

        return $query;
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function relatedVehicleModels()
    {
        return $this->belongsToMany(
            'App\Models\VehicleModel',
            'product_vehicle_models',
            'product_id',
            'vehicle_model_id'
        )->withTimestamps();
    }

    public function scopeWithUserClients($query, $userId)
    {
        return $query->whereHas('client', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }
}
