<?php

namespace App\Models\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseRepository
 */
class BaseRepository
{
    /**
     * Model Class Name.
     *
     * @var string String;
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param mixed $model Param.
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Create new Eloquent Query Builder instance
     *
     * @param string $method Param.
     * @param array  $params Param.
     * @return mixed
     */
    public function getQuery(string $method, array $params)
    {
        return call_user_func_array([$this->model, $method], $params);
    }

    /**
     * Create an instance of $this->model::class.
     *
     * @param array $data Param.
     * @return $this->model::class
     */
    public function create(array $data)
    {
        if (isset($data['preview']) && $data['preview'] === true) {
            return $this->createInstanceOfModel($data);
        }

        $options = isset($data['options']) ? $data['options'] : [];

        $item = call_user_func_array([$this->model, 'create'], [$data['data'], $options]);

        if (method_exists($this, 'afterCreate')) {
            $this->afterCreate($item, $data);
        }

        return $item;
    }

    /**
     * Create an instance of $this->model::class.
     *
     * @param array $data Param.
     * @return $this->model::class
     */
    public function createInstanceOfModel(array $data)
    {
        $options = isset($data['options']) ? $data['options'] : [];

        $item = new $this->model($data['data'], $options);

        $item->setAttribute('id', rand(0,99999999999));

        return $item;
    }

    /**
     * Update an instance of $this->model::class.
     *
     * @param array $data Param.
     * @return $this->model::class
     */
    public function update(array $data)
    {
        if (isset($data['ids' ])) {
            $item = $this->getQuery('whereIn', ['id', $data['ids' ] ]);
            unset($data['data' ]['ids']);
            $item->update($data['data']);

            return $item->get();
        }
        else if (isset($data['id'])) {
            $item = $this->find($data['id']);

            if (method_exists($this, 'beforeUpdateFilter')) {
                $this->beforeUpdateFilter($item, $data);
            }

            if ($item) {
                $options = isset($data['options']) ? $data['options'] : [];

                $item->update($data['data'], $options);

                if (method_exists($this, 'afterUpdate')) {
                    $this->afterUpdate($item, $data);
                }
            }

            return $item;
        }
        return null;
    }

    /**
     * Delete an instance of $this->model::class.
     *
     * @param array $data Param.
     * @return BaseRepository
     */
    public function delete(array $data)
    {
        $object = $this->find($data['id']);

        if ($object) {
            $object->delete();
        }

        if (method_exists($this, 'afterDelete')) {
            $this->afterDelete($object);
        }

        return $object;
    }

    /**
     * Delete instances of $this->model::class.
     *
     * @param array $data Param.
     * @return BaseRepository
     */
    public function batchDelete(array $data){
        $objects = $this->getQuery('whereIn', ['id', $data['ids']]);

        if ($objects && $objects->count()) {
            $objects->delete();
        }
        else {
            return null;
        }

        foreach($objects as $object) {
            if (method_exists($this, 'afterDelete')) {
                $this->afterDelete($object);
            }
        }

        return $objects;
    }

    /**
     * Find an instance of $this->model::class.
     *
     * @param integer $id     Param.
     * @param array   $params Param.
     * @return $this->model::class
     */
    public function find(int $id, array $params = [])
    {
        $query = null;

        if (isset($params['with']) && \count($params['with'])) {
            $query = \call_user_func_array([$this->model, 'with'], [$params['with']]);
        }

        if (isset($params['inputs'])) {
            $wheres = [];
            $inputs = array_filter((array)$params['inputs']);

            foreach ($inputs as $key => $val) {
                $permittedInputs = (new $this->model())->searchable;
                if ((is_numeric($key) && \is_array($val)) || \in_array($key, $permittedInputs, true)) {
                    $wheres[$key] = $val;
                }
            }

            if ($wheres) {
                $query = call_user_func_array([$query ?: $this->model, 'where'], [$wheres]);
            }
        }

        if (isset($params['wheres'])) {
            foreach ($params['wheres'] as $where) {
                $query = call_user_func_array([$query ?: $this->model, $where['method']], $where['args']);
            }
        }

        if (isset($params['havings'])) {
            foreach ($params['havings'] as $having) {
                $query = call_user_func_array([$query ?: $this->model, $having['method']], $having['args']);
            }
        }

        if (isset($params['columns'])) {
            $columns = array_filter(\is_array($params['columns']) ? $params['columns'] : explode(',', $params['columns']));
            $query = call_user_func_array([$query ?: $this->model, 'select'], $columns);
        }

        if (isset($params['inputs']['columns'])) {
            $inputColumns = explode(',', $params['inputs']['columns']);
            $query = call_user_func_array([$query ?: $this->model, 'select'], [$inputColumns]);
        }

        if (isset($params['scopes'])) {
            foreach ($params['scopes'] as $scope) {
                $query = call_user_func_array([$query ?: $this->model, $scope], []);
            }
        }

        return call_user_func_array([$query ?: $this->model, 'find'], [$id]);
    }

    /**
     * @param array $params Param.
     * @param boolean $paginate Pagination.
     * @return array|mixed
     */
    public function getAll(array $params = [], $paginate=true)
    {
        $query = null;

        if (isset($params['with']) && \count($params['with'])) {
            $query = call_user_func_array([$query ?: $this->model, 'with'], [$params['with']]);
        }

        if (isset($params['with_count']) && \count($params['with_count'])) {
            $query = call_user_func_array([$query ?: $this->model, 'withCount'], [$params['with_count']]);
        }

        if (isset($params['wheres'])) {
            foreach ($params['wheres'] as $where) {
                $query = call_user_func_array([$query ?: $this->model, $where['method']], $where['args']);
            }
        }

        $page = 1;
        $perPage = 15;
        if(isset($params['inputs'])) {
            $page = (int) (Arr::get($params['inputs'], 'page', 1));
            $perPage = (int) Arr::get($params['inputs'], 'perPage', 15);
        }

        if (isset($params['inputs']['with'])) {
            $inputWiths = \is_array($params['inputs']['with']) ? $params['inputs']['with'] : explode(',', $params['inputs']['with']);
            $query = call_user_func_array([$query ?: $this->model, 'with'], [$inputWiths]);
        }

        if (isset($params['inputs']['columns'])) {
            $inputColumns = \is_array($params['inputs']['columns']) ? $params['inputs']['columns'] : explode(',', $params['inputs']['columns']);
            $query = call_user_func_array([$query ?: $this->model, 'select'], [$inputColumns]);
        }

        if (isset($params['inputs']['orderBy'])) {
            $orderBy = $params['inputs']['orderBy'];
            $order = $params['inputs']['order'] ?? 'asc';
            $query = call_user_func_array([$query ?: $this->model, 'orderBy'], [$orderBy, $order]);
        }

        if ($page < 0) {
            $result = call_user_func_array([$query ?: $this->model, 'get'], []);

            if (isset($params['appends'])) {
                foreach ($result as $item) {
                    $item->append($params['appends']);
                }
            }
        }
        else {
            if ($paginate) {
                $items = call_user_func_array([$query ?: $this->model, 'paginate'], [$perPage]);

                if (isset($params['appends'])) {
                    foreach ($items as $item) {
                        $item->append($params['appends']);
                    }
                }
                $result = $this->result_for_paginate($items);
            }
            else {
                return $query;
            }
        }

        return $result;
    }

    /**
     * @param mixed $collection Param.
     * @return array
     */
    public function result_for_paginate($collection)
    {
        return [
            'items' => $collection->items(),
            'page' => $collection->currentPage(),
            'total' => $collection->total(),
            'pages' => $collection->lastPage(),
            'perPage' =>$collection->perPage()
        ];
    }

    /**
     * @param array $data Param.
     * @return bool The status of the insertion
     */
    public function batchInsert(array $data)
    {
        $options = isset($data['options']) ? $data['options'] : [];

        $result = call_user_func_array([$this->model, 'insert'], [$data['data'], $options]);

        return $result;
    }
}
