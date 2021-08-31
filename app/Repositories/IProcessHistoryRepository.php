<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 1:41 PM
 */

namespace App\Repositories;


use App\Models\Symbol;

interface IProcessHistoryRepository extends IRepository
{
    public function hasProcess();

    public function getLastFiftyDay();

    public function getLastFiveDay();
}
