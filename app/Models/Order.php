<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'manager_id',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_at',
        'current_address',
        'current_latitude',
        'current_longitude',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'delivered_at',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
