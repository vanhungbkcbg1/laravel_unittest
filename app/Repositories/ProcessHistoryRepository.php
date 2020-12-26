<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\ProcessHistory;
use App\Models\Symbol;
use App\Models\SymbolPrice;
use App\Models\User;

class ProcessHistoryRepository extends BaseRepository implements IProcessHistoryRepository
{

    public function getModel()
    {
        return ProcessHistory::class;
    }

    public function hasProcess()
    {
        $today =(new \DateTime())->format('Y-m-d');
        return $this->_model->where("date",$today)->first();
    }

}
