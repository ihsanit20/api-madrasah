<?php

namespace App\Traits\Scopes;

trait ScopeActive
{
    public function getActiveColumn()
    {
        return defined(static::class.'::ACTIVE')
            ? static::ACTIVE
            : 'is_active';
    }

    public function scopeActive($query): void
    {
        $query->where($this->getActiveColumn(), 1);
    }
}