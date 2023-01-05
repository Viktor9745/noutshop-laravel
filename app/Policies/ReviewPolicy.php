<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Review $review)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Review $review)
    {
        return ($user->id == $review->user_id)||($user->role->name!='user');
    }
    public function delete(User $user, Review $review)
    {
        return ($user->id == $review->user_id)||($user->role->name!='user');
    }
    public function restore(User $user, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Review $review)
    {
        //
    }
}
