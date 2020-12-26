<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Symbol extends Authenticatable
{
    use Notifiable;
    protected $table ="symbols";
    public $timestamps=false;
}
