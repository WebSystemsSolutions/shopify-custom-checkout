<?php

namespace App\Utils\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

/**
 * Trait FlusherTrait.
 */
trait FlusherTrait
{
    private array $deleteItems = [];

    /**
     * Add method flush to write and remove entity with her relations.
     *
     * @since v1.1.0 Add detach to HasMany relation type
     */
    public function flush()
    {
        parent::push();

        foreach ($this->deleteItems as $key => $items) {
            if ($this->{$key}() instanceof Relations\HasMany) {
                foreach ($items as $item) {
                    if (!$item->delete()) {
                        return false;
                    }
                }
            } elseif ($this->{$key}() instanceof Relations\BelongsToMany) {
                $this->{$key}()
                    ->detach($items);
            } else {
                throw new \RuntimeException('Relation is not implements.');
            }

            unset($this->deleteItems[$key]);
        }

        return true;
    }

    /**
     * @param string|Model $item
     */
    public function detachItem(string $relationName, $item): void
    {
        $this->relationsExists($relationName);

        if (isset($this->deleteItems[$relationName])) {
            $this->deleteItems[$relationName] = array_merge($this->deleteItems[$relationName], [$item]);

            return;
        }

        $this->deleteItems[$relationName] = [$item];
    }

    /**
     * @throws \RuntimeException
     */
    private function relationsExists(string $relationName): void
    {
        try {
            $this->getRelation($relationName);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Relation isn\'t present in model ' . static::class . '.');
        }
    }
}
