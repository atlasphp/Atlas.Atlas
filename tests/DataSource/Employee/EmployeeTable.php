<?php
/**
 * This table class was generated by Atlas. Changes will be overwritten.
 */
namespace Atlas\Orm\DataSource\Employee;

use Atlas\Orm\Table\AbstractTable;

/**
 * @inheritdoc
 */
class EmployeeTable extends AbstractTable
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function getColNames()
    {
        return [
            'id',
            'name',
            'building',
            'floor',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCols()
    {
        return [
            'id' => (object) [
                'name' => 'id',
                'type' => 'integer',
                'mapTo' => 'integer',
                'size' => null,
                'scale' => null,
                'notnull' => false,
                'default' => null,
                'autoinc' => true,
                'primary' => true,
            ],
            'name' => (object) [
                'name' => 'name',
                'type' => 'varchar',
                'mapTo' => 'string',
                'size' => 10,
                'scale' => null,
                'notnull' => true,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
            'building' => (object) [
                'name' => 'building',
                'type' => 'integer',
                'mapTo' => 'integer',
                'size' => null,
                'scale' => null,
                'notnull' => false,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
            'floor' => (object) [
                'name' => 'floor',
                'type' => 'integer',
                'mapTo' => 'integer',
                'size' => null,
                'scale' => null,
                'notnull' => false,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getPrimaryKey()
    {
        return [
            'id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAutoinc()
    {
        return 'id';
    }

    /**
     * @inheritdoc
     */
    public function getColDefaults()
    {
        return [
            'id' => null,
            'name' => null,
            'building' => null,
            'floor' => null,
        ];
    }
}
