<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\Symbol;
use App\Models\SymbolAnalyzed;
use App\Models\SymbolPrice;
use App\Models\User;

class SymbolAnalyzedRepository extends BaseRepository implements ISymbolAnalyzedRepository
{

    public function getModel()
    {
        return SymbolAnalyzed::class;
    }

}
