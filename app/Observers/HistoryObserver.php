<?php

namespace App\Observers;

use App\Models\History;
use Illuminate\Support\Facades\Auth;

class HistoryObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created($model): void
    {
        $this->saveHistory($model, __FUNCTION__);
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated($model): void
    {
        $this->saveHistory($model, __FUNCTION__);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted($model): void
    {
        $this->saveHistory($model, __FUNCTION__);
    }

    /**
     * Handle the Model "restored" event.
     */
    public function restored($model): void
    {
        $this->saveHistory($model, __FUNCTION__);
    }

    /**
     * Handle the Model "force deleted" event.
     */
    public function forceDeleted($model): void
    {
        $this->saveHistory($model, __FUNCTION__);
    }

    public function saveHistory($model, $event)
    {
        return $model->morphMany(History::class, 'model')->create([
            'message'       => $event,
            'changes'       => $model->getModelChanges(),
            'user_id'       => static::getUserId(),
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);
    }

    public static function getUserId()
    {
        return Auth::getDefaultDriver() === 'sanctum'
            ? (request()->user()->id ?? null)
            : Auth::id() ?? null;
    }
}
