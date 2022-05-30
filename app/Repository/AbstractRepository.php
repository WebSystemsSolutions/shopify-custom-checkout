<?php

namespace App\Repository;

use App\Utils\Interfaces\EntityManager;
use Closure;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

abstract class AbstractRepository
{
    public EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function query(): EloquentBuilder
    {
        return $this->model()::query();
    }

    /**
     * @param $column
     * @param null   $operator
     * @param null   $value
     * @param string $boolean
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and'): EloquentBuilder
    {
        return $this->query()
            ->where($column, $operator, $value, $boolean);
    }

    /**
     * @param string|int $id
     */
    public function get($id): Model
    {
        return $this->query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param string|int $id
     */
    public function find($id): ?Model
    {
        return $this->query()
            ->where('id', $id)
            ->first();
    }

    public function chunk(Closure $closure, int $count = 100, $where = null): void
    {
        $query = $this->query();

        if ($where !== null) {
            if ($where instanceof EloquentBuilder) {
                $query = $where;
            } elseif (is_array($where) === true && Arr::isAssoc($where) === true) {
                $query->where($where);
            } elseif (is_array($where) === true) {
                $query->where(...$where);
            } else {
                $query->whereRaw($where);
            }
        }

        $query->chunk($count, function ($models) use ($closure): void {
            foreach ($models as $model) {
                $closure($model);
            }
        });
    }

    /**
     * Get all users.
     */
    public function getAll(int $perPage = 20): LengthAwarePaginator
    {
        return $this->query()
            ->paginate($perPage);
    }

    public function findAll(): Collection
    {
        return $this->query()
            ->get();
    }

    /**
     * @param array|string $where
     */
    public function getByCondition($where): Model
    {
        return $this->query()
            ->where($where)
            ->firstOrFail();
    }

    public function persist(Model $model): void
    {
        $this->em->persist($model);
    }

    /**
     * @throws Exception
     */
    public function remove(Model $model): void
    {
        $this->em->remove($model);
    }

    /**
     * @return Model
     */
    abstract protected function model(): string;
}
