<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SymbolPrice extends Authenticatable
{
    use Notifiable;
    protected $table = "symbol_prices";
    public $timestamps = false;

    public function setDate(\DateTime $value)
    {
        dump("setdata");
        $this->date = $value->format("Y-m-d");
    }

    public function getDate()
    {
        if (is_null($this->date)) {
            return "";
        }

        return (new \DateTime($this->date))->format('Y-m-d');
    }
}
