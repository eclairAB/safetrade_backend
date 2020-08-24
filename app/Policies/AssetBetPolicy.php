<?php

namespace App\Policies;

use App\User;
use App\UserAsset;
use App\UserBet;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssetBetPolicy
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
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  UserBet  $userBet
     * @return boolean
     */
    public function view(User $user, UserBet $userBet)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @param  UserAsset $userasset
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->id<7) //allow if bots and superuser
            return true;
        else {
            //create only if the user has an existing user asset.
            $user_id = UserAsset::where('user_id',$user->id)->get('user_id')->first()->toarray();
            return  $user_id['user_id'] === $user->id;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  UserBet  $userBet
     * @return boolean
     */
    public function update(User $user, UserBet $userBet)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  UserBet  $userBet
     * @return boolean
     */
    public function delete(User $user, UserBet $userBet)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  UserBet  $userBet
     * @return boolean
     */
    public function restore(User $user, UserBet $userBet)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  UserBet  $userBet
     * @return boolean
     */
    public function forceDelete(User $user, UserBet $userBet)
    {
        return false;
    }
}
