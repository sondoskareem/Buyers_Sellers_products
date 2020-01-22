<?php

namespace App;
use Illuminate\Support\Str;

use App\Transformers\UserTransformer;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\VerifyApiEmail;

class User extends Authenticatable implements JWTSubject 
{
    use Notifiable , SoftDeletes;
    protected $date = ['deleted_at'];
    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';
    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';
    protected $table = 'users';
    public $transformer = UserTransformer::class;
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    //mutators and accessor to store lowecase in db
    public function setNameAttribute($name){
        $this->attributes['name'] = strtolower($name);
    }


    // public function getNameAttribute($name){
    //     return ucwords($name);
    // }

    public function setEmailAttribute($email){
        $this->attributes['email'] = strtolower($email);
    }

    // public function getEmailAttribute($email){
    //     return \ucwords($email);
    // }
    
    public function isVerified(){
        return $this->verified == (boolean)User::VERIFIED_USER; //comparation
    }

    public function isAdmin(){
        return $this->admin == User::ADMIN_USER;
    }

    public static  function generateVerificationCode(){
        return Str::random(40);
    }
}
