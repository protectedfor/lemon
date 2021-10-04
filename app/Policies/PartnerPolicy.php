<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PartnerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param Organization $organization
     * @return Response|bool
     */
    public function viewAny(User $user, Organization $organization)
    {
        return $user->id === $organization->user_id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Organization $organization
     * @param Partner $partner
     * @return Response|bool
     */
    public function view(User $user, Organization $organization, Partner $partner)
    {
        return $user->id === $organization->user_id && $organization->id === $partner->organization_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Organization $organization
     * @return Response|bool
     */
    public function create(User $user, Organization $organization)
    {
        return $user->id == $organization->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Partner $partner
     * @param Organization $organization
     * @return Response|bool
     */
    public function update(User $user, Organization $organization, Partner $partner)
    {
        return $user->id === $organization->user_id && $organization->id === $partner->organization_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Organization $organization
     * @param Partner $partner
     * @return Response|bool
     */
    public function delete(User $user, Organization $organization, Partner $partner)
    {
        return $user->id === $organization->user_id && $organization->id === $partner->organization_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Partner $partner
     * @return Response|bool
     */
    public function restore(User $user, Partner $partner)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Partner $partner
     * @return Response|bool
     */
    public function forceDelete(User $user, Partner $partner)
    {
        //
    }
}
