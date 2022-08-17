<?php

namespace App\Policies;

use App\Contract;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Contract  $contract
     * @return mixed
     */
    public function view(User $user, Contract $contract)
    {
        return $contract->users()->find($user->id);
    }



    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Contract  $contract
     * @return mixed
     */
    public function update(User $user, Contract $contract)
    {
        return $contract->users()->find($user->id);
    }


}
