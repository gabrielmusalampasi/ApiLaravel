<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
  protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'pseudo', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    const UPDATED_AT = null;

    public static function checkIfExist($email){
        //faire le code pour verifier que l'email n'esr pas utiliser ou le pseudo
        if($user = User::where('email',$email)->first()){
            return $user;
        }
        return null;
    }
    public static function getUser($key,$value){
        if(!$user = self::where($key,$value)->first()){
            return null;
        }
        return $user;
    }
    public static function check($token){
        //faire le code pour verifier que l'email n'esr pas utiliser ou le pseudo
        if($user = User::where('code',$token)->first()){
            return $user;
        }
        return null;
    }

}
