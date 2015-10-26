<?php
namespace Atlas;

use Atlas\Exception;
use Atlas\Mapper\AbstractRecord;
use Atlas\Mapper\MapperLocator;
use SplObjectStorage;

class Transaction
{
    /**
     *
     * A MapperLocator to insert, update, and delete records.
     *
     * @var MapperLocator
     *
     */
    protected $mapperLocator;

    /**
     *
     * Write connections extracted from the mappers.
     *
     * @var SplObjectStorage
     *
     */
    protected $connections;

    /**
     *
     * All planned work.
     *
     * @var array
     *
     */
    protected $plan = [];

    /**
     *
     * All completed work.
     *
     * @var array
     *
     */
    protected $completed = [];

    /**
     *
     * The exception that occurred during exec(), causing a rollback.
     *
     * @var Exception
     *
     */
    protected $exception;

    /**
     *
     * The work that caused the exception.
     *
     * @var Work
     *
     */
    protected $failed;

    /**
     *
     * Constructor.
     *
     * @param MapperLocator $mapperLocator The Mapper locator.
     *
     */
    public function __construct(MapperLocator $mapperLocator)
    {
        $this->mapperLocator = $mapperLocator;
        $this->connections = new SplObjectStorage();
    }

    /**
     *
     * Gets the connections.
     *
     * @return array
     *
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     *
     * Gets the planned work.
     *
     * @return array
     *
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     *
     * Gets the completed work.
     *
     * @return array
     *
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     *
     * Gets the exception that caused a rollback in exec().
     *
     * @return Exception
     *
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     *
     * Gets the work that caused the exception in exec().
     *
     * @return Work
     *
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     *
     * Adds a callable to invoke as part of the transaction plan.
     *
     * @param string $label A label for the planned work.
     *
     * @param callable $callable The callable to invoke.
     *
     * @param mixed ...$args Arguments to pass to the callable.
     *
     */
    public function plan($label, callable $callable, ...$args)
    {
        $this->plan[] = $this->newWork($label, $callable, $args);
        return $this;
    }

    /**
     *
     * Specifies a record to insert as part of the transaction.
     *
     * @param AbstractRecord $record The record to insert.
     *
     * @return null
     *
     */
    public function insert(AbstractRecord $record)
    {
        $this->planMapperWork('insert', $record);
        return $this;
    }

    /**
     *
     * Specifies a record to update as part of the transaction.
     *
     * @param AbstractRecord $record The record to update.
     *
     * @return null
     *
     */
    public function update(AbstractRecord $record)
    {
        $this->planMapperWork('update', $record);
        return $this;
    }

    /**
     *
     * Specifies a record to delete as part of the transaction.
     *
     * @param AbstractRecord $record The record to delete.
     *
     * @return null
     *
     */
    public function delete(AbstractRecord $record)
    {
        $this->planMapperWork('delete', $record);
        return $this;
    }

    /**
     *
     * Adds mapper-specific work to the transaction plan.
     *
     * @param string $method The mapper method to call.
     *
     * @param AbstractRecord $record Use this record to locate the mapper,
     * and call the mapper using this record.
     *
     * @return null
     *
     */
    protected function planMapperWork($method, AbstractRecord $record)
    {
        $mapper = $this->mapperLocator->get($record);
        $this->connections->attach($mapper->getTable()->getWriteConnection());
        $this->plan(
            "$method " . get_class($record),
            [$mapper, $method],
            $record
        );
    }

    /**
     *
     * Returns a new Work instance.
     *
     * @param string $label A label for the planned work.
     *
     * @param callable $callable The callable to invoke for the work.
     *
     * @param array $args Arguments to pass to the callable.
     *
     * @return Work
     *
     */
    protected function newWork($label, callable $callable, array $args)
    {
        return new Work($label, $callable, $args);
    }

    /**
     *
     * Executes the transaction plan.
     *
     * @return bool True if the transaction succeeded, false if not.
     *
     * @throws Exception when attempting to re-execute a transaction.
     *
     * @todo Blow up if there is no plan.
     *
     * @todo Blow up if there are no write connections.
     *
     */
    public function exec()
    {
        $prior = $this->completed || $this->failed || $this->exception;
        if ($prior) {
            throw new Exception('Cannot re-execute a prior transaction.');
        }

        try {
            $this->begin();
            $this->execPlan();
            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->exception = $e;
            $this->rollBack();
            return false;
        }
    }

    /**
     *
     * Executes all planned work.
     *
     * @return mixed
     *
     */
    protected function execPlan()
    {
        foreach ($this->plan as $work) {
            $this->failed = $work;
            $work();
            $this->completed[] = $work;
            $this->failed = null;
        }
    }

    /**
     *
     * Begins a transaction on all connections.
     *
     * @return null
     *
     */
    protected function begin()
    {
        foreach ($this->connections as $connection) {
            $connection->beginTransaction();
        }
    }

    /**
     *
     * Commits the transaction on each connection.
     *
     * @return null
     *
     */
    protected function commit()
    {
        foreach ($this->connections as $connection) {
            $connection->commit();
        }
    }

    /**
     *
     * Rolls back the transaction on each connection.
     *
     * @return null
     *
     */
    protected function rollBack()
    {
        foreach ($this->connections as $connection) {
            $connection->rollBack();
        }
    }
}
