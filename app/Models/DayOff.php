<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DayOff extends Authenticatable
{
    use Notifiable;
    protected $table ="day_offs";
    public $timestamps=false;

    public $fillable=['day'];
    public $dates=['day'];
    public $dateFormat ='d/m/Y';

    public function setDayAttribute($value){
        $this->attributes['day']=date_create_from_format('d/m/Y',$value)->format('Y-m-d');
    }

    public function getDayAttribute($value){
        return date_create_from_format('Y-m-d',$value)->format('d/m/Y');
    }
}
