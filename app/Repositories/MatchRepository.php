<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\Match;

class MatchRepository extends BaseRepository implements IMatchRepository
{

    public function getModel()
    {
        return Match::class;
    }
}