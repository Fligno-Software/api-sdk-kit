<?php

namespace Fligno\ApiSdkKit\DataFactories;

use Fligno\ApiSdkKit\Models\AuditLog;
use Fligno\StarterKit\Abstracts\BaseDataFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class AuditLogDataFactory
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2022-05-04
 */
class AuditLogDataFactory extends BaseDataFactory
{
    /**
     * @var string
     */
    public string $user_id;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $method;

    /**
     * @var array
     */
    public array $headers;

    /**
     * @var Collection
     */
    public Collection $data;

    /**
     * @var int
     */
    public int $status;

    /**
     * @var string
     */
    public string $deleted_at;


    /**
     * @return Builder
     * @example User::query()
     */
    public function getBuilder(): Builder
    {
        return AuditLog::query();
    }
}
