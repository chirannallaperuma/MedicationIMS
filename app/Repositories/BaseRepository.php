<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * order
     *
     * @var undefined
     */
    protected $order = null;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * BaseRepository create method
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * find
     *
     * @param  mixed $id
     * @return void
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $newDetails
     * @return void
     */
    public function update($id, array $newDetails)
    {
        $data = $this->model->find($id);

        $data->update($newDetails);

        return $data;
    }

    /**
     * findAll
     *
     * @return void
     */
    public function findAll()
    {
        return $this->model->all();
    }

    /**
     * updateOrCreateLaravel
     *
     * @param  mixed $attributes
     * @param  mixed $values
     * @return void
     */
    public function updateOrCreateLaravel(array $attributes, array $values = array())
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * findAllPaginated
     *
     * @param  mixed $relations
     * @param  mixed $paginate
     * @return void
     */
    public function findAllPaginated($relations = array(), $paginate = 15)
    {
        $model = $this->model;
        if ($this->order != null) {
            $model = $model->orderBy($this->order[0], $this->order[1]);
        }

        //eager load relations
        foreach ($relations as $relation) {
            $model->with($relation);
        }

        return $model->paginate($paginate);
    }

    /**
     * updateOrCreateAndReturnModel
     *
     * @param  mixed $input
     * @param  mixed $key
     * @return void
     */
    public function updateOrCreateAndReturnModel($input, $key = 'id')
    {
        if (!empty($input[$key])) {
            $resource = $this->model->firstOrNew(array($key => $input[$key]));
        } else {
            $resource = $this->model;
        }

        $resource->fill($input);

        if (!$resource->save()) return false;

        return $resource;
    }

    /**
     * findByField
     *
     * @param  mixed $id
     * @return void
     */
    public function findByField($id)
    {
        return $this->model->find($id);
    }

    /**
     * whereFirstWithRelations
     *
     * @param  mixed $with
     * @param  mixed $column
     * @param  mixed $value
     * @return void
     */
    public function whereFirstWithRelations($with = [], $column, $value)
    {
        return $this->model->with($with)->where($column, $value)->first();
    }
}
