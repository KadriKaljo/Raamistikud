<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasFormatedDate;
class Author extends Model
{
    use HasFactory;
    use HasFormatedDate;

    protected $guarded = ['id'];

    protected $appends = [ 
        'created_at_formatted',
        'updated_at_formatted',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}

