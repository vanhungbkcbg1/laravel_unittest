<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SymbolAnalyzed extends Authenticatable
{
    use Notifiable;
    protected $table = "symbol_analyzed";
    public $timestamps = false;

}
