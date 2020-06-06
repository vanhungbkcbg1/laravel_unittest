<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:22 PM
 */

namespace App\Repositories;


use App\Models\Category;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{

    public function getModel()
    {
        return Category::class;
    }
}
