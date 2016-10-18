<?php
/**
 * This table class was generated by Atlas. Changes will be overwritten.
 */
namespace Atlas\Orm\DataSource\Test2;

use Atlas\Orm\Table\AbstractTable;

/**
 * @inheritdoc
 */
class Test2Table extends AbstractTable
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'test2';
    }

    /**
     * @inheritdoc
     */
    public function getColNames()
    {
        return [
            'id',
            'test1_id',
            'name',
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
                'type' => 'int',
                'size' => 11,
                'scale' => null,
                'notnull' => true,
                'default' => null,
                'autoinc' => true,
                'primary' => true,
            ],
            'test1_id' => (object) [
                'name' => 'test1_id',
                'type' => 'char',
                'size' => 10,
                'scale' => null,
                'notnull' => true,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
            'name' => (object) [
                'name' => 'name',
                'type' => 'varchar',
                'size' => 10,
                'scale' => null,
                'notnull' => true,
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
            'test1_id' => null,
            'name' => null,
        ];
    }
}
