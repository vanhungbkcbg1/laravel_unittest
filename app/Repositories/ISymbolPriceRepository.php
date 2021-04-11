<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 1:41 PM
 */

namespace App\Repositories;


use App\Models\NewSymbol;
use App\Models\Symbol;

interface ISymbolPriceRepository extends IRepository
{
    public function getAverageFifteenDay(NewSymbol $symbol);

    public function deleteOldData($date);
}
