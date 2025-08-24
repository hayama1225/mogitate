<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Season;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'season',
    ];

    public function seasons()
    {
        #timestamps()を使用する場合、モデルのリレーションで->withTimestamps()必要
        return $this->belongsToMany(Season::class)->withTimestamps();
    }
}
