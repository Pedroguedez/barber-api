<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    protected $table = 'working_hours';

    protected $fillable = [
        'employee_id',
        'weekday',
        'opening_morning',
        'closing_morning',
        'late_opening',
        'late_closing',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
