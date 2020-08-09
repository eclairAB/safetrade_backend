<?php

namespace App\Policies;

use App\User;
use App\UserAsset;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserAssetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->is_superuser;
    }

//    /**
//     * Determine whether the user can view the model.
//     *
//     * @param  \App\User  $user
//     * @param  \App\UserAsset  $userAsset
//     * @return mixed
//     */
//    public function view(User $user, UserAsset $userAsset)
//    {
//        return true;
//    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_superuser;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\UserAsset  $userAsset
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->is_superuser;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\UserAsset  $userAsset
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->is_superuser;
    }

//    /**
//     * Determine whether the user can restore the model.
//     *
//     * @param  \App\User  $user
//     * @param  \App\UserAsset  $userAsset
//     * @return mixed
//     */
//    public function restore(User $user, UserAsset $userAsset)
//    {
//        return true;
//    }

//    /**
//     * Determine whether the user can permanently delete the model.
//     *
//     * @param  \App\User  $user
//     * @param  \App\UserAsset  $userAsset
//     * @return mixed
//     */
//    public function forceDelete(User $user, UserAsset $userAsset)
//    {
//        return true;
//    }
}
