<?php
/**
 * Created by PhpStorm.
 * User: hungnguyenv4
 * Date: 5/15/2019
 * Time: 2:03 PM
 */

namespace App\Repositories;


use Illuminate\Support\Collection;

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
     * @return Collection
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

    public function delete($model)
    {
        return $model->delete();
    }

    public function clear()
    {
        return $this->_model->whereNotNull('id')->delete();
    }

    public function paginate($perPage,$orders=[],$credential=[],$page=1)
    {
        if($orders){
            foreach ($orders as $key =>$value){
                $this->_model =$this->_model->orderBy($key,$value);
            }
        }

        if($credential){
            foreach ($credential as $key=>$value){
                $this->_model= $this->_model->where($key,$value);
            }
        }
        return $this->_model->paginate($perPage,["*"],"page",$page);
    }

    public function findByCondition($conditions=[]){
        foreach ($conditions as $item){
            $this->_model =$this->_model->where($item[0],$item[1],$item[2]);
        }
        return $this->_model->get();
    }

    public function findOneByCondition($conditions=[]){
        foreach ($conditions as $item){
            $this->_model->where($item[0],$item[1],$item[2]);
        }
        return $this->_model->first();
    }


}
