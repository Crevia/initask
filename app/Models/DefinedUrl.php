<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefinedUrl extends Model
{
    use HasFactory, HasUlids;
    protected $fillable = [
        "original",
        "short",
        "full_url"
    ];
}
