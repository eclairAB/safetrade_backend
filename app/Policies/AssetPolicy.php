<?php

namespace App\Policies;

use App\User;
//use App\Asset;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return boolean
     */
    public function viewAny(User $user)
    {
        return $user->is_superuser;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  //Asset  $asset
     * @return boolean
     */
//    public function view(User $user, Asset $asset)
    public function view(User $user)
    {
        return $user->is_superuser;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->is_superuser;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  //Asset  $asset
     * @return boolean
     */

//    public function update(User $user, Asset $asset)
    public function update(User $user)
    {
        return $user->is_superuser;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  //Asset $asset
     * @return true
     */
//    public function delete(User $user, Asset $asset)
    public function delete(User $user)
    {
        return $user->is_superuser;
    }

//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param  User  $user
//     * @param  Asset $asset
//     * @return true
//     */
//    public function restore(User $user, Asset $asset)
//    {
//        return true;
//    }

//    /**
//     * Determine whether the user can permanently delete the model.
//     *
//     * @param  User  $user
//     * @param  Asset  $asset
//     * @return true
//     */
//    public function forceDelete(User $user, Asset $asset)
//    {
//        return true;
//    }
}
