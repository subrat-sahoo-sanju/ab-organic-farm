<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Attach to any model to auto-record admin changes.
 */
class AuditObserver
{
    protected array $sensitive = ['password', 'remember_token'];

    public function created(Model $model): void
    {
        $this->log('create', $model, null, $this->clean($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $this->log('update', $model, $this->clean($model->getOriginal()), $this->clean($changes));
    }

    public function deleted(Model $model): void
    {
        $this->log('delete', $model, $this->clean($model->getOriginal()), null);
    }

    protected function log(string $action, Model $model, ?array $old, ?array $new): void
    {
        if (! auth()->check()) {
            return; // system actions tracked via status histories instead
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'role_label' => auth()->user()->primaryRole(),
            'action' => $action,
            'subject_type' => $model::class,
            'subject_id' => $model->getKey(),
            'subject_label' => $model->getAttribute('name')
                ?? $model->getAttribute('order_number')
                ?? $model->getAttribute('code')
                ?? '#'.$model->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()?->ip(),
            'user_agent' => substr((string) request()?->userAgent(), 0, 500),
        ]);
    }

    protected function clean(array $attributes): array
    {
        return collect($attributes)
            ->except($this->sensitive)
            ->map(fn ($v) => is_array($v) ? json_encode($v) : $v)
            ->all();
    }
}
