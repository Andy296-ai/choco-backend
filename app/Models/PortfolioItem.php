<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'image_path', 'title'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
