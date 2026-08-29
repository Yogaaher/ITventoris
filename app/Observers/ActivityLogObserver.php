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

        $hidden = $model->getHidden();

        if ($action === 'created') {
            $newValues = $model->getAttributes();
            if (!empty($hidden)) {
                $newValues = array_diff_key($newValues, array_flip($hidden));
            }
        }

        if ($action === 'updated') {
            $newValues = $model->getChanges();
            unset($newValues['updated_at']);
            if (!empty($hidden)) {
                $newValues = array_diff_key($newValues, array_flip($hidden));
            }
            if (empty($newValues)) return;
            $oldValues = array_intersect_key($model->getOriginal(), $newValues);
        }

        if ($action === 'deleted') {
            $oldValues = $model->getAttributes();
            if (!empty($hidden)) {
                $oldValues = array_diff_key($oldValues, array_flip($hidden));
            }
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