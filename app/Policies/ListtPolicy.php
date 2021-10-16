<?php

namespace App\Policies;

use App\Models\Listt;
use App\Models\User;
use App\Models\Workboard;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListtPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listt  $listt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Listt $listt)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listt  $listt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Listt $listt)
    {
        // Allow board owner and board members
        return $user->id == $listt->workboard->user->id ||
            ($listt->workboard->members->contains($user->id) );

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listt  $listt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Listt $listt)
    {
        // Allow board owner and board members
        return $user->id == $listt->workboard->user->id ||
            ($listt->workboard->members->contains($user->id));

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listt  $listt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Listt $listt)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listt  $listt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Listt $listt)
    {
        //
    }
}
