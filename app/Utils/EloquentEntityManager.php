<?php

namespace App\Utils;

use App\Utils\Interfaces\EntityManager as EntityManagerInterface;
use App\Utils\Traits\FlusherTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use RuntimeException;
use Webmozart\Assert\Assert;

class EloquentEntityManager implements EntityManagerInterface
{
    /**
     * @var Model[]|Collection
     */
    private Collection $persists;

    /**
     * @var Model[]|Collection
     */
    private Collection $deletes;

    private TransactionManager $transaction;

    public function __construct(TransactionManager $transaction)
    {
        $this->transaction = $transaction;
        $this->persists = new Collection();
        $this->deletes = new Collection();
    }

    public function persist(object $entity): void
    {
        Assert::isInstanceOf($entity, Model::class, 'Object not instance of Laravel Eloquent');

        if ($this->persists->has($key = spl_object_hash($entity)) === true) {
            return;
        }

        $this->persists->put($key, $entity);
    }

    public function remove(object $entity): void
    {
        Assert::isInstanceOf($entity, Model::class, 'Object not instance of Laravel Eloquent');

        if ($this->deletes->has($key = spl_object_hash($entity)) === true) {
            return;
        }

        $this->deletes->put($key, $entity);
    }

    /**
     * @throws \Exception
     */
    public function flush(): void
    {
        $this->transaction->wrap(function (): void {
            $this->deletes->each(function (Model $delete): void {
                if ($delete->delete() === false) {
                    throw new RuntimeException(sprintf('Model %s not deleted!', get_class($delete)));
                }
            });

            $this->persists->each(function (Model $persist): void {
                if ($persist->{$this->getMethod($persist)}() === false) {
                    throw new RuntimeException(sprintf('Model %s not saved!', get_class($persist)));
                }
            });

            $this->deletes = new Collection();
            $this->persists = new Collection();
        });
    }

    private function getMethod(Model $model): string
    {
        if (method_exists($model, 'flush') && in_array(FlusherTrait::class, class_uses($model), true)) {
            /** @see FlusherTrait::flush() */
            return 'flush';
        }

        /** @see Model::push() */
        return 'push';
    }
}
