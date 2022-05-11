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

    public function getAverageFifteenDay(NewSymbol $symbol, $fromDate)
    {
        $today = date('Y-m-d');
//        $fromDate= date('Y-m-d',strtotime('-5 weekday'));
        return $this->_model->where("date",'<',$today)->where('date','>=',$fromDate)->where('symbol',$symbol->name)->average("volume");
    }

    /**
     * Delete older than 50 days data
     */
    public function deleteOldData($date){
        $this->_model->where('date','<',$date)->delete();
    }

    public function getValueByDate(NewSymbol $symbol, $date)
    {
        return $this->_model->where('date', $date)->where('symbol',$symbol->name)->first();
    }
}
