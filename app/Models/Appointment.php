<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'client_id',
        'barber_id',
        'service_id',
        'data',
        'time',
        'status',
        'notes',
    ];

    /**
     * Retorna a lista de status permitidos para um agendamento.
     *
     * @return array
     */
    public static function getAllowedStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELED,
            self::STATUS_COMPLETED,
        ];
    }

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
