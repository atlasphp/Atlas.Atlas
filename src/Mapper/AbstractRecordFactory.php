<?php
namespace Atlas\Mapper;

use Atlas\Table\AbstractRow;
use InvalidArgumentException;

abstract class AbstractRecordFactory
{
    abstract public function getRecordClass();

    abstract public function getRecordSetClass();

    // row can be array or Row object
    public function newRecord(AbstractRow $row, array $relatedFields)
    {
        $recordClass = $this->getRecordClass();
        return new $recordClass($row, new Related($relatedFields));
    }

    // rowSet can be array of Rows, or RowSet object
    public function newRecordSetFromRows($rows, array $relatedFields)
    {
        $records = [];
        foreach ($rows as $row) {
            $records[] = $this->newRecord($row, $relatedFields);
        }
        return $this->newRecordSet($records);
    }

    public function newRecordSet(array $records = [])
    {
        $recordSetClass = $this->getRecordSetClass();
        return new $recordSetClass($records, $this->getRecordClass());
    }

    public function assertRecordClass(AbstractRecord $record)
    {
        $recordClass = $this->getRecordClass();
        if (! $record instanceof $recordClass) {
            $actual = get_class($record);
            throw new InvalidArgumentException("Expected {$recordClass}, got {$actual} instead");
        }
    }
}
