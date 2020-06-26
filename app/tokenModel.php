<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tokenModel extends Model
{
    protected $table = 'token';
    const UPDATED_AT = null;
    const CREATED_AT = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'expired_at', 'user_id',
    ];

}
