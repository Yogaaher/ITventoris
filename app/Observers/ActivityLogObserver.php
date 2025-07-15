<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityLogObserver
{
    private function logActivity(string $action, Model $model): void
    {
        $user = Auth::user();
        $newValues = null;
        $oldValues = null;

        if ($action === 'created') {
            $newValues = $model->getAttributes();
        }

        if ($action === 'updated') {
            $newValues = $model->getChanges();
            unset($newValues['updated_at']);
            if (empty($newValues)) return;
            $oldValues = array_intersect_key($model->getOriginal(), $newValues);
        }

        if ($action === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        DB::table('activity_logs')->insert([
            'user_id' => $user->id ?? null,
            'user_name' => $user->name ?? 'System',
            'user_role' => $user->role ?? 'N/A',
            'table_name' => $model->getTable(),
            'action' => $action,
            'loggable_id' => $model->getKey(),
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function created(Model $model): void
    {
        $this->logActivity('created', $model);
    }

    public function updated(Model $model): void
    {
        $this->logActivity('updated', $model);
    }

    public function deleted(Model $model): void
    {
        $this->logActivity('deleted', $model);
    }
}