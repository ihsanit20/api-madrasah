<?php

namespace App\Traits;

use App\Models\History;
use App\Observers\HistoryObserver;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

trait HasHistories
{
    protected static function bootHasHistories()
    {
        return static::observe(HistoryObserver::class);
    }

    public function histories():MorphMany
    {
        return $this->morphMany(History::class, 'model')
            ->latest();
    }

    public function getModelChanges()
    {
        $changes = Arr::except($this->getDirty(), $this->ignoreHistoryColumns());

        $changed = [];

        foreach ($changes as $key => $value) {
            array_push($changed, [
                'key' => $key,
                'old' => $this->getOriginal($key),
                'new' => $value
            ]);
        }

        return $changed;
    }

    public function ignoreHistoryColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
            'password',
            'remember_token',
            'email_verified_at',
        ];
    }
}