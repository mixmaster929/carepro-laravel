<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'clientnumber', 'name', 'email', 'password','role_id','telephone','status','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function adminRoles(){
        return $this->belongsToMany(AdminRole::class);
    }

    public function candidateFields()
    {
        return $this->belongsToMany(CandidateField::class)->withPivot(['value']);
    }

    public function candidate(){
        return $this->hasOne(Candidate::class);
    }

    public function employerFields(){
        return $this->belongsToMany(EmployerField::class)->withPivot(['value']);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function userAttachments(){
        return $this->hasMany(UserAttachment::class);
    }

    public function userNotes(){
        return $this->hasMany(UserNote::class);
    }

    public function employer(){
        return $this->hasOne(Employer::class);
    }

    public function employmentComments(){
        return $this->hasMany(EmploymentComment::class);
    }

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function billingAddresses(){
        return $this->hasMany(BillingAddress::class);
    }

    public function interviews(){
        return $this->hasMany(Interview::class);
    }

    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function userTests(){
        return $this->hasMany(UserTest::class);
    }

    public function contracts(){
        return $this->belongsToMany(Contract::class)->withPivot('signed');
    }

}
