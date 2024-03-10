<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'name',
        'description',
        'file_type',
        'file'
    ];
    protected $hidden = [
        'updated_at',
        'creator_id',

    ];

    protected static function booted(): void
    {
        static::addGlobalScope('creator', function (Builder $builder) {
//            $builder->with('creator');
            $builder->where('creator_id', Auth::id());
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
