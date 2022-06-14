<?php

namespace Fligno\ApiSdkKit\Console\Commands;

use Fligno\ApiSdkKit\Models\AuditLog;
use Illuminate\Console\Command;

/**
 * Class DeleteUnattachedAuditLogsCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2022-05-04
 */
class DeleteUnattachedAuditLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ask:delete-unattached-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unattached audit logs.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $unattachedLogs = AuditLog::attached(false)->where('created_at', '<=', now()->subHour());

        config('api-sdk-kit.audit_log_force_delete_unattached') ? $unattachedLogs->forceDelete() : $unattachedLogs->delete();

        return self::SUCCESS;
    }
}
