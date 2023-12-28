<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuthor
{
    protected static function bootHasAuthor()
    {
        static::creating(function ($model) {
            $model->author_id = auth()->id() ?? null;
        });
    }

    public function getAuthorIdColumn():String
    {
        return defined(static::class.'::AUTHOR_ID')
            ? static::AUTHOR_ID
            : 'author_id';
    }

    public function author():BelongsTo
    {
        return $this->belongsTo(User::class, $this->getAuthorIdColumn());
    }
}