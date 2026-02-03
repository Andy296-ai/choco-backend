<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'type',
        'value',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
