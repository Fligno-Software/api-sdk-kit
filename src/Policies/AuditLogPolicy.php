<?php

namespace Fligno\ApiSdkKit\Policies;

use App\Models\User;
use Fligno\ApiSdkKit\Models\AuditLog;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Class AuditLogPolicy
 *
 * @author James Carlo Luchavez <jamescarlo.luchavez@fligno.com>
 */
class AuditLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User     $user
     * @param  AuditLog $auditLog
     * @return Response|bool
     */
    public function view(User $user, AuditLog $auditLog): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User     $user
     * @param  AuditLog $auditLog
     * @return Response|bool
     */
    public function update(User $user, AuditLog $auditLog): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User     $user
     * @param  AuditLog $auditLog
     * @return Response|bool
     */
    public function delete(User $user, AuditLog $auditLog): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User     $user
     * @param  AuditLog $auditLog
     * @return Response|bool
     */
    public function restore(User $user, AuditLog $auditLog): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User     $user
     * @param  AuditLog $auditLog
     * @return Response|bool
     */
    public function forceDelete(User $user, AuditLog $auditLog): Response|bool
    {
        return Response::allow();
    }
}
