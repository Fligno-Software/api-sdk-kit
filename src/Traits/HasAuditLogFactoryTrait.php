<?php

namespace Fligno\ApiSdkKit\Traits;

use Database\Factories\AuditLogFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Trait HasAuditLogFactoryTrait
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
trait HasAuditLogFactoryTrait
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return AuditLogFactory::new();
    }
}
