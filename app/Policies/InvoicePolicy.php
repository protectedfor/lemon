<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * @param User $user
     * @param Organization $organization
     * @param Partner $partner
     * @param Invoice $invoice
     * @return bool
     */
    public function view(User $user, Invoice $invoice)
    {
        return $user->id === $invoice->organization->user_id && $invoice->organization->id === $invoice->partner->organization_id && $invoice->organization_id == $invoice->organization->id && $invoice->partner_id == $invoice->partner->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Organization $organization
     * @param Partner $partner
     * @return bool
     */
    public function create(User $user, Organization $organization, Partner $partner)
    {
        return $user->id === $organization->user_id && $organization->id === $partner->organization_id;
    }

    /**
     * Determine whether the user can update the model.
     * @param User $user
     * @param Organization $organization
     * @param Partner $partner
     * @param Invoice $invoice
     * @return bool
     */
    public function update(User $user, Invoice $invoice)
    {
        return $user->id === $invoice->organization->user_id && $invoice->organization->id === $invoice->partner->organization_id && $invoice->organization_id == $invoice->organization->id && $invoice->partner_id == $invoice->partner->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return Response|bool
     */
    public function delete(User $user, Invoice $invoice)
    {
        return $user->id === $invoice->organization->user_id && $invoice->organization->id === $invoice->partner->organization_id && $invoice->organization_id == $invoice->organization->id && $invoice->partner_id == $invoice->partner->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return Response|bool
     */
    public function restore(User $user, Invoice $invoice)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Invoice $invoice
     * @return Response|bool
     */
    public function forceDelete(User $user, Invoice $invoice)
    {
        //
    }
}
