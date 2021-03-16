<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\DayOff;
use Illuminate\Support\Collection;

class DayOffRepository extends BaseRepository implements IDayOffRepository
{

    public function getModel()
    {
        return DayOff::class;
    }

    public function findByDay($day):Collection
    {
        return $this->_model->where('day',$day)->get();
    }


}
