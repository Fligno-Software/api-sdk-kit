<?php

namespace Fligno\ApiSdkKit\Feature\Models;

use Tests\TestCase;

/**
 * Class AuditLogTest
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class AuditLogTest extends TestCase
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
