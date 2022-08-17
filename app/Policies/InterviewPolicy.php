<?php

namespace App\Policies;

use App\Interview;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InterviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any interviews.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the interview.
     *
     * @param  \App\User  $user
     * @param  \App\Interview  $interview
     * @return mixed
     */
    public function view(User $user, Interview $interview)
    {
        return $user->id == $interview->user_id;
    }

    /**
     * Determine whether the user can create interviews.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the interview.
     *
     * @param  \App\User  $user
     * @param  \App\Interview  $interview
     * @return mixed
     */
    public function update(User $user, Interview $interview)
    {
        //
    }

    /**
     * Determine whether the user can delete the interview.
     *
     * @param  \App\User  $user
     * @param  \App\Interview  $interview
     * @return mixed
     */
    public function delete(User $user, Interview $interview)
    {
        //
    }

    /**
     * Determine whether the user can restore the interview.
     *
     * @param  \App\User  $user
     * @param  \App\Interview  $interview
     * @return mixed
     */
    public function restore(User $user, Interview $interview)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the interview.
     *
     * @param  \App\User  $user
     * @param  \App\Interview  $interview
     * @return mixed
     */
    public function forceDelete(User $user, Interview $interview)
    {
        //
    }
}
