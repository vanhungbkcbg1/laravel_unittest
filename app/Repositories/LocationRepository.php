<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\Location;

class LocationRepository extends BaseRepository implements ILocationRepository
{

    public function getModel()
    {
        return Location::class;
    }
}