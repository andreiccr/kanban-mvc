<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workboard;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkboardPolicy
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
     * @param  \App\Models\Workboard  $workboard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Workboard $workboard)
    {
        return $user->id == $workboard->user->id || $workboard->members->contains($user->id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workboard  $workboard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Workboard $workboard)
    {
        return $user->id == $workboard->user->id ||
            ($workboard->members->contains($user->id) );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workboard  $workboard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Workboard $workboard)
    {
        return $user->id == $workboard->user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workboard  $workboard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Workboard $workboard)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workboard  $workboard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Workboard $workboard)
    {
        //
    }
}
