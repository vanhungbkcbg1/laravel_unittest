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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProcessHistoryRepository extends BaseRepository implements IProcessHistoryRepository
{

    public function getModel()
    {
        return ProcessHistory::class;
    }

    public function hasProcess()
    {
        $today = (new \DateTime())->format('Y-m-d');
        return $this->_model->where("date", $today)->first();
    }

    public function getLastFiftyDay()
    {
        /** @var Collection $data */
        $data = $this->_model->orderBy('id', 'desc')->take(50)->get();
        if ($data->count() == 50) {
            return $data[49]->date;
        }
        return null;
    }

    public function getLastFiveDay()
    {
        /** @var Collection $data */
        $data = $this->_model->orderBy('id', 'desc')->take(5)->get();
        if ($data->count() == 5) {
            return $data[4]->date;
        }
        return null;
    }

    public function getYesterday()
    {
        $data = $this->_model->orderBy('id', 'desc')->take(1)->get();
        return $data[0]->date;
    }

    public function getLastTwoDay()
    {
        $data = $this->_model->orderBy('id', 'desc')->take(2)->get();
        return $data[1]->date;
    }
}
