<?php

namespace Approval\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Modification extends Model
{
    /**
     * The attributes that can't be filled.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'modifications' => 'json',
    ];

    /**
     * Get models that the modification belongs to.
     *
     * @return MorphTo
     */
    public function modifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get models that the ignited this modification.
     *
     * @return MorphTo
     */
    public function modifier(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Return Approval relations via direct relation.
     *
     * @return HasMany
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(config('approval.models.approval', \Approval\Models\Approval::class));
    }

    /**
     * Return Disapproval relations via direct relation.
     *
     * @return HasMany
     */
    public function disapprovals(): HasMany
    {
        return $this->hasMany(config('approval.models.disapproval', \Approval\Models\Disapproval::class));
    }

    /**
     * Get the number of approvals reamaining for the changes
     * to be approved and approval will close.
     *
     * @return int
     */
    public function getApproversRemainingAttribute(): int
    {
        return $this->approvers_required - $this->approvals()->count();
    }

    /**
     * Get the number of disapprovals reamaining for the changes
     * to be disapproved and approval will close.
     *
     * @return int
     */
    public function getDisapproversRemainingAttribute(): int
    {
        return $this->disapprovers_required - $this->disapprovals()->count();
    }

    /**
     * Convenience alias of ApproversRemaining attribute.
     *
     * @return int
     */
    public function getApprovalsRemainingAttribute(): int
    {
        return $this->approversRemaining;
    }

    /**
     * Convenience alias of DisapproversRemaining attribute.
     *
     * @return int
     */
    public function getDisapprovalsRemainingAttribute(): int
    {
        return $this->disapproversRemaining;
    }

    /**
     * Force apply changes to modifiable.
     *
     * @return void
     */
    public function forceApprovalUpdate()
    {
        $this->modifiable->applyModificationChanges($this, true);
    }

    /**
     * Scope to only include active modifications.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeActiveOnly($query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope to only include inactive modifications.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeInactiveOnly($query): Builder
    {
        return $query->where('active', false);
    }

    /**
     * Scope to only retrieve changed models.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeChanges($query): Builder
    {
        return $query->where('is_update', true);
    }

    /**
     * Scope to only retrieve created models.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeCreations($query): Builder
    {
        return $query->where('is_update', false);
    }
}
