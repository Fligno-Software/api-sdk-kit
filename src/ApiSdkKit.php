<?php

namespace Fligno\ApiSdkKit;

use Fligno\ApiSdkKit\Abstracts\BaseAuditLogHandler;
use Fligno\ApiSdkKit\Models\AuditLog;
use Fligno\StarterKit\Traits\HasTaggableCacheTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Class ApiSdkKit
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class ApiSdkKit
{
    use HasTaggableCacheTrait;

    /**
     * @return string
     */
    public function getMainTag(): string
    {
        return 'ask';
    }

    /**
     * @var array
     */
    protected array $auditLogHandlers = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Grab copy from cache if exists
        $this->auditLogHandlers = $this->getAuditLogHandlers()->toArray();
    }


    /**
     * @return Collection
     */
    public function getAuditLogHandlers(): Collection
    {
        return $this->getCache($this->getTags(), 'audit_log_handlers', function () {
            return collect($this->auditLogHandlers);
        });
    }

    /**
     * @param Model $model
     * @param AuditLog $audit_log
     * @return BaseAuditLogHandler|null
     */
    public function getAuditLogHandler(Model $model, AuditLog $audit_log): ?BaseAuditLogHandler
    {
        if ($handler = $this->getAuditLogHandlers()->get(get_class($model))) {
            return new $handler($model, $audit_log);
        }

        return null;
    }

    /**
     * @param string $model_class
     * @param string $audit_log_handler_class
     * @param bool $override
     * @return void
     */
    public function addAuditLogHandler(string $model_class, string $audit_log_handler_class, bool $override = false): void
    {
        $model_class = get_class(new $model_class);
        $audit_log_handler_class = get_class(new $audit_log_handler_class);

        // Check if $model_class is valid
        if (! is_subclass_of($model_class, Model::class)) {
            throw new RuntimeException('Invalid Eloquent model.');
        }

        // Check AuditLogHandler is valid
        if (! is_subclass_of($audit_log_handler_class, BaseAuditLogHandler::class)) {
            throw new RuntimeException('Invalid AuditLogHandler class.');
        }

        // Grab copy of Collection
        $handlers = $this->getAuditLogHandlers();

        // Add to collection if model class not yet exists
        // Add to collection if model class exists but AuditLogHandler is different and $override is false
        // Throw error if model class exists and AuditLogHandler is different and $override is true

        $differentHandler = false;

        if (! $handlers->has($model_class) || ($differentHandler = $handlers->get($model_class) !== $audit_log_handler_class)) {
            if ($differentHandler && ! $override) {
                throw new RuntimeException('AuditLogHandler already exists for specified model: ' . $model_class);
            }

            // Add to Collection
            $this->auditLogHandlers = $handlers->put($model_class, $audit_log_handler_class)->toArray();

            // Clear Cache
            apiSdkKit()->clearCache();

            // Invoke to generate new cache
            $this->getAuditLogHandlers();
        }
    }
}
