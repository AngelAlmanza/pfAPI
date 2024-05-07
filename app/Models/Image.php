<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'base64_url',
        'type'
    ];

    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }

    public function pets()
    {
        return $this->belongsToMany(Pet::class);
    }

    public function veterinaries()
    {
        return $this->belongsToMany(Veterinary::class);
    }
}
