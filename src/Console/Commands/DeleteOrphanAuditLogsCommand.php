<?php

namespace Fligno\ApiSdkKit\Console\Commands;

use Fligno\ApiSdkKit\Models\AuditLog;
use Illuminate\Console\Command;

/**
 * Class DeleteOrphanAuditLogsCommand
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 * @since 2022-05-04
 */
class DeleteOrphanAuditLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ask:delete-orphan-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove orphan audit logs.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $orphanLogs = AuditLog::query()
            ->whereNull('audit_loggable_type')
            ->whereNull('audit_loggable_id')
            ->where('created_at', '<=', now()->subHour());

        config('api-sdk-kit.audit_log_force_delete_orphan') ? $orphanLogs->forceDelete() : $orphanLogs->delete();

        return self::SUCCESS;
    }
}
