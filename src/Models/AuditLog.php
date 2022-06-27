<?php

namespace Fligno\ApiSdkKit\Models;

use Fligno\ApiSdkKit\Traits\HasAuditLogFactoryTrait;
use Fligno\StarterKit\Casts\AsCompressedArrayCast;
use Fligno\StarterKit\Casts\AsCompressedCollectionCast;
use Fligno\StarterKit\Interfaces\HasHttpStatusCodeInterface;
use Fligno\StarterKit\Traits\UsesUUIDTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\Pure;

/**
 * Class AuditLog
 *
 * @method static Builder attached(bool $bool = true) Get logs that has or lacks attachment
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class AuditLog extends Model implements HasHttpStatusCodeInterface
{
    use UsesUUIDTrait;
    use SoftDeletes;
    use HasAuditLogFactoryTrait;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $casts = [
        'headers' => AsCompressedArrayCast::class,
        'data' => AsCompressedCollectionCast::class,
    ];

    /********
     * RELATIONSHIPS
     ********/

    /**
     * @return MorphTo
     */
    public function auditLoggable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo|null
     */
    public function user(): ?BelongsTo
    {
        if ($model = starterKit()->getUserModel()) {
            return $this->belongsTo($model);
        }

        return null;
    }

    /*****
     * ACCESSORS & MUTATORS
     *****/

    public function scopeAttached(Builder $builder, bool $bool = true)
    {
        $columns = ['audit_loggable_type', 'audit_loggable_id'];

        return $builder->when(
            $bool,
            fn (Builder $builder) => $builder->whereNotNull($columns),
            fn (Builder $builder) => $builder->whereNull($columns)
        );
    }

    /********
     * OTHER METHODS
     ********/

    /**
     * Determine if the request was successful.
     *
     * @return bool
     */
    public function successful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    /**
     * Determine if the response code was "OK".
     *
     * @return bool
     */
    public function ok(): bool
    {
        return $this->status === 200;
    }

    /**
     * Determine if the response was a 401 "Unauthorized" response.
     *
     * @return bool
     */
    public function unauthorized(): bool
    {
        return $this->status === 401;
    }

    /**
     * Determine if the response was a 403 "Forbidden" response.
     *
     * @return bool
     */
    public function forbidden(): bool
    {
        return $this->status === 403;
    }

    /**
     * Determine if the response indicates a client or server error occurred.
     *
     * @return bool
     */
    #[Pure] public function failed(): bool
    {
        return $this->serverError() || $this->clientError();
    }

    /**
     * Determine if the response indicates a client error occurred.
     *
     * @return bool
     */
    public function clientError(): bool
    {
        return $this->status >= 400 && $this->status < 500;
    }

    /**
     * Determine if the response indicates a server error occurred.
     *
     * @return bool
     */
    public function serverError(): bool
    {
        return $this->status >= 500;
    }
}
