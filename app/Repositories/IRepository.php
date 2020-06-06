<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 1:41 PM
 */

namespace App\Repositories;


interface IRepository
{
    public function getAll();

    public function find($id);

    public function create(array $attributes);

    public function update($id,array $attributes);

}