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
use App\Models\SymbolPrice;
use App\Models\User;

class SymbolPriceRepository extends BaseRepository implements ISymbolPriceRepository
{

    public function getModel()
    {
        return SymbolPrice::class;
    }

    public function getAverageFifteenDay(NewSymbol $symbol)
    {
        $today = (new \DateTime())->format("Y-m-d");
        return $this->_model->where("date",'<',$today)->where('symbol',$symbol->name)->average("volume");
    }

    /**
     * Delete older than 50 days data
     */
    public function deleteOldData($date){
        $this->_model->where('date','<',$date)->delete();
    }
}
