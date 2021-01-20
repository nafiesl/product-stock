<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductUnit;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductUnitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the product_unit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductUnit  $productUnit
     * @return mixed
     */
    public function view(User $user, ProductUnit $productUnit)
    {
        // Update $user authorization to view $productUnit here.
        return true;
    }

    /**
     * Determine whether the user can create product_unit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductUnit  $productUnit
     * @return mixed
     */
    public function create(User $user, ProductUnit $productUnit)
    {
        // Update $user authorization to create $productUnit here.
        return true;
    }

    /**
     * Determine whether the user can update the product_unit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductUnit  $productUnit
     * @return mixed
     */
    public function update(User $user, ProductUnit $productUnit)
    {
        // Update $user authorization to update $productUnit here.
        return true;
    }

    /**
     * Determine whether the user can delete the product_unit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProductUnit  $productUnit
     * @return mixed
     */
    public function delete(User $user, ProductUnit $productUnit)
    {
        // Update $user authorization to delete $productUnit here.
        return true;
    }
}
