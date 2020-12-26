<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ProcessHistory extends Authenticatable
{
    use Notifiable;
    protected $table ="process_history";
    public $timestamps=false;

    protected $fillable =['date'];
}
