<?php

namespace App\Utils;

use Exception;
use Illuminate\Database\ConnectionInterface;

/**
 * Class TransactionManager.
 */
class TransactionManager
{
    private ConnectionInterface $db;

    /**
     * TransactionManager constructor.
     */
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * @throws Exception
     */
    public function wrap(callable $function): void
    {
        try {
            $this->db->beginTransaction();

            $function();

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();

            throw $e;
        }
        //end try
    }
}
