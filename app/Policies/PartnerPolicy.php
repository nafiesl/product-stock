<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Partner;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the partner.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partner  $partner
     * @return mixed
     */
    public function view(User $user, Partner $partner)
    {
        // Update $user authorization to view $partner here.
        return true;
    }

    /**
     * Determine whether the user can create partner.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partner  $partner
     * @return mixed
     */
    public function create(User $user, Partner $partner)
    {
        // Update $user authorization to create $partner here.
        return true;
    }

    /**
     * Determine whether the user can update the partner.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partner  $partner
     * @return mixed
     */
    public function update(User $user, Partner $partner)
    {
        // Update $user authorization to update $partner here.
        return true;
    }

    /**
     * Determine whether the user can delete the partner.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Partner  $partner
     * @return mixed
     */
    public function delete(User $user, Partner $partner)
    {
        // Update $user authorization to delete $partner here.
        return true;
    }
}
