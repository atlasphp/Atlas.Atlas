<?php
namespace Atlas\Orm\Relationship;

use Atlas\Orm\Mapper\RecordInterface;

class OneToOne extends AbstractRelationship
{
    public function stitchIntoRecords(
        array $nativeRecords,
        callable $custom = null
    ) {
        $this->fix();

        $select = $this->selectForRecords($nativeRecords, $custom);
        $foreignRecordsArray = $select->fetchRecords();

        foreach ($nativeRecords as $nativeRecord) {
            $nativeRecord->{$this->name} = false;
            foreach ($foreignRecordsArray as $foreignRecord) {
                if ($this->recordsMatch($nativeRecord, $foreignRecord)) {
                    $nativeRecord->{$this->name} = $foreignRecord;
                }
            }
        }
    }
}
