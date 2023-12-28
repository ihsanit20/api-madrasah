<?php

namespace App\Traits\Scopes;

trait ScopeFilter
{
    public function scopeFilter($query):void
    {
        $fields = request()->fields ?? "*";
        
        $select = explode(",", $fields);

        $query->select($select);
    }
}