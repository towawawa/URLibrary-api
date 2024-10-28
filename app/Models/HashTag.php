<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HashTag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function urlLibraries()
    {
        return $this->belongsToMany(UrlLibrary::class, 'has_tag_url_libraries');
    }
}
