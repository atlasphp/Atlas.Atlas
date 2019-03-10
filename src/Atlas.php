<?php
/**
 *
 * This file is part of Atlas for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Atlas\Orm;

use Atlas\Orm\Mapper\MapperLocator;
use Atlas\Orm\Mapper\MapperSelect;
use Atlas\Orm\Mapper\MapperInterface;
use Atlas\Orm\Mapper\RecordInterface;
use Atlas\Orm\Mapper\RecordSetInterface;
use Exception;

/**
 *
 * An entry point for all Atlas functionality.
 *
 * @package atlas/orm
 *
 */
class Atlas
{
    /**
     *
     * The most recent exception.
     *
     * @var Exception
     *
     */
    protected $exception;

    /**
     *
     * A locator for all Mappers in the system.
     *
     * @var MapperLocator
     *
     */
    protected $mapperLocator;

    /**
     *
     * A prototype transaction.
     *
     * @var Transaction
     *
     */
    protected $prototypeTransaction;

    /**
     *
     * Constructor.
     *
     * @param MapperLocator $mapperLocator A locator for all Mappers.
     *
     * @param Transaction $prototypeTransaction A prototype Transaction.
     *
     */
    public function __construct(
        MapperLocator $mapperLocator,
        Transaction $prototypeTransaction
    ) {
        $this->mapperLocator = $mapperLocator;
        $this->prototypeTransaction = $prototypeTransaction;
    }

    /**
     *
     * Returns a Mapper from the locator by its class name.
     *
     * @param string $mapperClass The Mapper class name.
     *
     * @return MapperInterface
     *
     */
    public function mapper(string $mapperClass) : MapperInterface
    {
        return $this->mapperLocator->get($mapperClass);
    }

    /**
     *
     * Returns a new Record from a Mapper.
     *
     * @param string $mapperClass Use this Mapper to create the new Record.
     *
     * @param array $cols Populate the Record with these values.
     *
     * @return RecordInterface
     *
     */
    public function newRecord(string $mapperClass, array $cols = []) : RecordInterface
    {
        return $this->mapper($mapperClass)->newRecord($cols);
    }

    /**
     *
     * Returns a new RecordSet from a Mapper.
     *
     * @param string $mapperClass Use this Mapper to create the new Record.
     *
     * @return RecordSetInterface
     *
     */
    public function newRecordSet(string $mapperClass) : RecordSetInterface
    {
        return $this->mapper($mapperClass)->newRecordSet();
    }

    /**
     *
     * Fetches one Record by its primary key value from a Mapper, optionally
     * with relateds.
     *
     * @param string $mapperClass Fetch the Record through this Mapper.
     *
     * @param mixed $primaryVal The primary key value; a scalar in the case of
     * simple keys, or an array of key-value pairs for composite keys.
     *
     * @param array $with Return the Record with these relateds stitched in.
     *
     * @return ?RecordInterface
     *
     */
    public function fetchRecord(string $mapperClass, $primaryVal, array $with = []) : ?RecordInterface
    {
        return $this->mapper($mapperClass)->fetchRecord($primaryVal, $with);
    }

    /**
     *
     * Fetches one Record by column-value equality pairs from a Mapper,
     * optionally with relateds.
     *
     * @param string $mapperClass Fetch the Record through this Mapper.
     *
     * @param array $whereEquals The column-value equality pairs.
     *
     * @param array $with Return the Record with these relateds stitched in.
     *
     * @return ?RecordInterface
     *
     */
    public function fetchRecordBy(string $mapperClass, array $whereEquals, array $with = []) : ?RecordInterface
    {
        return $this->mapper($mapperClass)->fetchRecordBy($whereEquals, $with);
    }

    /**
     *
     * Fetches an array of Records by primary key values from a Mapper, optionally with
     * relateds.
     *
     * @param string $mapperClass Fetch the Records through this Mapper.
     *
     * @param array $primaryVals The primary key values. Each element in the
     * array is a scalar in the case of simple keys, or an array of key-value
     * pairs for composite keys.
     *
     * @param array $with Return each Record with these relateds stitched in.
     *
     * @return array An array of Records.
     *
     */
    public function fetchRecords(string $mapperClass, array $primaryVals, array $with = []) : array
    {
        return $this->mapper($mapperClass)->fetchRecords($primaryVals, $with);
    }

    /**
     *
     * Fetches an array of Records by column-value equality pairs from a Mapper,
     * optionally with relateds.
     *
     * @param string $mapperClass Fetch the Records through this Mapper.
     *
     * @param array $whereEquals The column-value equality pairs.
     *
     * @param array $with Return each Record with these relateds stitched in.
     *
     * @return array An array of Records.
     *
     */
    public function fetchRecordsBy(string $mapperClass, array $whereEquals, array $with = []) : array
    {
        return $this->mapper($mapperClass)->fetchRecordsBy($whereEquals, $with);
    }

    /**
     *
     * Fetches a RecordSet by primary key values from a Mapper, optionally with
     * relateds.
     *
     * @param string $mapperClass Fetch the RecordSet through this Mapper.
     *
     * @param array $primaryVals The primary key values. Each element in the
     * array is a scalar in the case of simple keys, or an array of key-value
     * pairs for composite keys.
     *
     * @param array $with Return each Record with these relateds stitched in.
     *
     * @return RecordSetInterface|array A RecordSet on success, or an empty
     * array on failure.
     *
     */
    public function fetchRecordSet(string $mapperClass, array $primaryVals, array $with = []) : ?RecordSetInterface
    {
        return $this->mapper($mapperClass)->fetchRecordSet($primaryVals, $with);
    }

    /**
     *
     * Fetches a RecordSet by column-value equality pairs from a Mapper,
     * optionally with relateds.
     *
     * @param string $mapperClass Fetch the RecordSet through this Mapper.
     *
     * @param array $whereEquals The column-value equality pairs.
     *
     * @param array $with Return each Record with these relateds stitched in.
     *
     * @return RecordSetInterface|array A RecordSet on success, or an empty
     * array on failure.
     *
     */
    public function fetchRecordSetBy(string $mapperClass, array $whereEquals, array $with = []) : ?RecordSetInterface
    {
        return $this->mapper($mapperClass)->fetchRecordSetBy($whereEquals, $with);
    }

    /**
     *
     * Returns a new select object from a Mapper.
     *
     * @param string $mapperClass Create the select object through this Mapper.
     *
     * @param array $whereEquals A series of column-value equality pairs for the
     * WHERE clause.
     *
     * @return MapperSelect
     *
     */
    public function select(string $mapperClass, array $whereEquals = []) : MapperSelect
    {
        return $this->mapper($mapperClass)->select($whereEquals);
    }

    /**
     *
     * Returns a new Transaction for a unit of work.
     *
     * @return Transaction
     *
     */
    public function newTransaction() : Transaction
    {
        return clone $this->prototypeTransaction;
    }

    /**
     *
     * Insert a Record through its Mapper as a unit-of-work transaction.
     *
     * @param RecordInterface $record Insert the Row for this Record.
     *
     * @return bool
     *
     */
    public function insert(RecordInterface $record) : bool
    {
        return $this->transact('insert', $record);
    }

    /**
     *
     * Update a Record through its Mapper as a unit-of-work transaction.
     *
     * @param RecordInterface $record Update the Row for this Record.
     *
     * @return bool
     *
     */
    public function update(RecordInterface $record) : bool
    {
        return $this->transact('update', $record);
    }

    /**
     *
     * Delete a Record through its Mapper as a unit-of-work transaction.
     *
     * @param RecordInterface $record Delete the Row for this Record.
     *
     * @return bool
     *
     */
    public function delete(RecordInterface $record) : bool
    {
        return $this->transact('delete', $record);
    }

    /**
     *
     * Persists a Record through its Mapper as a unit-of-work transaction. This will
     * insert/update/delete the Record as appropriate; further, it will
     * recursively persist all of its loaded relationships.
     *
     * @param RecordInterface $record Persist this Record along with its
     * relateds.
     *
     * @return bool
     *
     */
    public function persist(RecordInterface $record) : bool
    {
        return $this->transact('persist', $record);
    }

    /**
     *
     * Returns the most-recent exception from the unit-of-work transaction.
     *
     * @return ?Exception
     *
     */
    public function getException() : ?Exception
    {
        return $this->exception;
    }

    /**
     *
     * Turns profiling on and off.
     *
     * @param bool $profiling True to turn on; false to turn off.
     *
     * @return void
     *
     */
    public function setProfiling($profiling = true) : void
    {
        $this->mapperLocator
            ->getTableLocator()
            ->getConnectionManager()
            ->getConnectionLocator()
            ->setProfiling($profiling);
    }

    /**
     *
     * Gets the query profiles.
     *
     * @param bool $profiling True to turn on; false to turn off.
     *
     * @return array
     *
     */
    public function getProfiles() : array
    {
        return $this->mapperLocator
            ->getTableLocator()
            ->getConnectionManager()
            ->getConnectionLocator()
            ->getProfiles();
    }

    /**
     *
     * Gets the MapperLocator.
     *
     * @return MapperLocator
     *
     */
    public function getMapperLocator() : MapperLocator
    {
        return $this->mapperLocator;
    }

    /**
     *
     * Manually begins a transaction through the ConnectionManager.
     *
     * @return void
     *
     */
    public function beginTransaction() : void
    {
        $this->mapperLocator
            ->getTableLocator()
            ->getConnectionManager()
            ->beginTransaction();
    }

    /**
     *
     * Manually commits a transaction through the ConnectionManager.
     *
     * @return void
     *
     */
    public function commit() : void
    {
        $this->mapperLocator
            ->getTableLocator()
            ->getConnectionManager()
            ->commit();
    }

    /**
     *
     * Manually rolls back a transaction through the ConnectionManager.
     *
     * @return void
     *
     */
    public function rollBack() : void
    {
        $this->mapperLocator
            ->getTableLocator()
            ->getConnectionManager()
            ->rollBack();
    }

    /**
     *
     * Is the ConnectionManager in a transaction?
     *
     * @return bool
     *
     */
    public function inTransaction() : bool
    {
        return $this->mapperLocator
            ->getTableLocator()
            ->getConnectionManager()
            ->inTransaction();
    }

    /**
     *
     * Performs a unit-of-work transaction, *unless* there is already a
     * transaction in place, in which case the work becomes part of that
     * pre-existing transaction.
     *
     * @param string $method The transaction work to perform.
     *
     * @param RecordInterface $record The record to work with.
     *
     * @return bool
     *
     */
    protected function transact(string $method, RecordInterface $record) : bool
    {
        if ($this->inTransaction()) {
            return $this->mapper($record->getMapperClass())->$method($record);
        }

        $this->exception = null;
        $transaction = $this->newTransaction();
        $transaction->$method($record);

        if (! $transaction->exec()) {
            $this->exception = $transaction->getException();
            return false;
        }

        $completed = $transaction->getCompleted();
        $work = $completed[0];
        return $work->getResult();
    }
}
