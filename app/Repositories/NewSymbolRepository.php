<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\NewSymbol;
use App\Models\Symbol;

class NewSymbolRepository extends BaseRepository implements INewSymbolRepository
{

    public function getModel()
    {
        return NewSymbol::class;
    }

}
