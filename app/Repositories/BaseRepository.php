<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:03 PM
 */

namespace App\Repositories;


abstract class BaseRepository implements IRepository
{
    protected $_model;

    public function __construct()
    {
        $this->setModel();
    }

    /***
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function setModel()
    {
        $this->_model = app()->make($this->getModel());

    }

    public abstract function getModel();

    /***Get All
     * @return mixed
     */
    public function getAll()
    {
        return $this->_model->all();
    }

    /*** update
     * @param $id
     * @param array $attributes
     * @return bool
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    /*** create new record
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    /*** find by id
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->_model->find($id);
    }

    public function delete($model){
        return $model->delete();
    }

}
