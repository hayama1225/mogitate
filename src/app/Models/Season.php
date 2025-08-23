<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = ['name']; #カラム名に合わせる('season'の方がわかりやすい？)

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
