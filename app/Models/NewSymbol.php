<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NewSymbol extends Authenticatable
{
    use Notifiable;
    protected $table ="new_symbols";
    public $timestamps=false;

    public $fillable=['name'];
}
