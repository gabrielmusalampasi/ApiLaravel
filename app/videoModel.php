<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class videoModel extends Model
{
    protected $table = 'video';
    const UPDATED_AT = null;
    const CREATED_AT = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'duration', 'user_id', 'source', 'created_at', 'view', 'enabled',
    ];


    public static function getvideo($key,$value){
        if(!$video = self::where($key,$value)->first()){
            return null;
        }
        return $video;
    }
}
