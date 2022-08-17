<?php

namespace App\Policies;

use App\Employment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmploymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any employments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the employment.
     *
     * @param  \App\User  $user
     * @param  \App\Employment  $employment
     * @return mixed
     */
    public function view(User $user, Employment $employment)
    {
        return $user->id == $employment->employer->user_id;
    }

    /**
     * Determine whether the user can create employments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the employment.
     *
     * @param  \App\User  $user
     * @param  \App\Employment  $employment
     * @return mixed
     */
    public function update(User $user, Employment $employment)
    {
        return $user->id == $employment->employer->user_id;
    }

    /**
     * Determine whether the user can delete the employment.
     *
     * @param  \App\User  $user
     * @param  \App\Employment  $employment
     * @return mixed
     */
    public function delete(User $user, Employment $employment)
    {
        return $user->id == $employment->employer->user_id;
    }

    /**
     * Determine whether the user can restore the employment.
     *
     * @param  \App\User  $user
     * @param  \App\Employment  $employment
     * @return mixed
     */
    public function restore(User $user, Employment $employment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the employment.
     *
     * @param  \App\User  $user
     * @param  \App\Employment  $employment
     * @return mixed
     */
    public function forceDelete(User $user, Employment $employment)
    {
        //
    }
}
