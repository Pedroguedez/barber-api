<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'barber_id',
        'service_id',
        'data',
        'time',
        'status',
        'notes',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
