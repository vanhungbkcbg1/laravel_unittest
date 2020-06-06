<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\User;

class UserRepository extends BaseRepository implements IUserRepository
{

    public function getModel()
    {
        return User::class;
    }
}