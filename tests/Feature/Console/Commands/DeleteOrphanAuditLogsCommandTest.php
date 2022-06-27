<?php

namespace Fligno\ApiSdkKit\Feature\Console\Commands;

use Tests\TestCase;

/**
 * Class DeleteOrphanAuditLogsCommandTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class DeleteOrphanAuditLogsCommandTest extends TestCase
{
    /**
     * Example Test
     *
     * @test
     */
    public function example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
