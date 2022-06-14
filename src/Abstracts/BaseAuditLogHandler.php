<?php

namespace Fligno\ApiSdkKit\Abstracts;

use Fligno\ApiSdkKit\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseAuditLogHandler
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
abstract class BaseAuditLogHandler
{
    /**
     * @param Model|null $model
     * @param AuditLog|null $auditLog
     */
    public function __construct(protected ?Model $model = null, protected ?AuditLog $auditLog = null)
    {
        //
    }

    /**
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @return AuditLog|null
     */
    public function getAuditLog(): ?AuditLog
    {
        return $this->auditLog;
    }

    /**
     * @return void
     */
    abstract public function handle(): void;
}
