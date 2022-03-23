<?php

namespace Database\Factories;

// Model
use Fligno\ApiSdkKit\Models\AuditLog;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class AuditLog
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
