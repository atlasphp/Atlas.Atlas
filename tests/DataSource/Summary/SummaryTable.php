<?php
namespace Atlas\Orm\DataSource\Summary;

use Atlas\Orm\Table\AbstractTable;

class SummaryTable extends AbstractTable
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'summaries';
    }

    /**
     * @inheritdoc
     */
    public function getColNames()
    {
        return [
            'thread_id',
            'reply_count',
            'view_count',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCols()
    {
        return [
            'thread_id' => (object) [
                'name' => 'thread_id',
                'type' => 'integer',
                'size' => null,
                'scale' => null,
                'notnull' => false,
                'default' => null,
                'autoinc' => false,
                'primary' => true,
            ],
            'reply_count' => (object) [
                'name' => 'reply_count',
                'type' => 'integer',
                'size' => null,
                'scale' => null,
                'notnull' => true,
                'default' => null,
                'autoinc' => false,
                'primary' => false,
            ],
            'view_count' => (object) [
                'name' => 'view_count',
                'type' => 'integer',
                'size' => null,
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
        return 'thread_id';
    }

    /**
     * @inheritdoc
     */
    public function getAutoinc()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getColDefaults()
    {
        return [
            'thread_id' => null,
            'reply_count' => null,
            'view_count' => null,
        ];
    }
}
