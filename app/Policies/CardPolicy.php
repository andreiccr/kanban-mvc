<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\Listt;
use App\Models\User;
use App\Models\Workboard;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
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
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Card $card)
    {
        // Allow board owner and board members
        return $user->id == $card->listt->workboard->user->id ||
            ($card->listt->workboard->members->contains($user->id) );
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
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Card $card)
    {
        // Allow board owner and board members
        return $user->id == $card->listt->workboard->user->id ||
            ($card->listt->workboard->members->contains($user->id) );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Card $card)
    {
        // Allow board owner and board members
        return $user->id == $card->listt->workboard->user->id ||
            ($card->listt->workboard->members->contains($user->id) );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Card $card)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Card  $card
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Card $card)
    {
        //
    }
}
