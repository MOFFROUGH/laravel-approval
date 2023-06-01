<?php

namespace Approval\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Disapproval extends Model
{
    /**
     * The attributes that can't be filled.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get models that the disapproval belongs to.
     *
     * @return MorphTo
     */
    public function disapprover(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Return Modification relation via direct relation.
     *
     * @return BelongsTo
     */
    public function modification(): BelongsTo
    {
        return $this->belongsTo(config('approval.models.modification', \Approval\Models\Modification::class));
    }
}
