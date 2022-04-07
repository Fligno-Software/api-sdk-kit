<?php

namespace Fligno\ApiSdkKit\Models;

use Fligno\ApiSdkKit\Traits\HasAuditLogFactoryTrait;
use Fligno\StarterKit\Traits\UsesUUIDTrait;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use JetBrains\PhpStorm\Pure;

/**
 * Class AuditLog
 *
 * Note:
 * By default, models and factories inside a package need to explicitly connect with each other.
 * Thanks to `fligno/boilerplate-generator` package, once you create a factory file, it will also create a trait.
 * The trait then should be used inside the concerned model.
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class AuditLog extends Model
{
    use UsesUUIDTrait, SoftDeletes, HasAuditLogFactoryTrait;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'headers',
        'data',
        'deleted_at',
    ];

    protected $casts = [
        'headers' => 'array',
        'data' => AsCollection::class,
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

    //

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
