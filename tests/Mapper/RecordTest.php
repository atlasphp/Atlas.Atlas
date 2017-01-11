<?php
namespace Atlas\Orm\Mapper;

use Atlas\Orm\Exception;
use Atlas\Orm\Table\Row;
use Atlas\Orm\Table\Primary;

class RecordTest extends \PHPUnit_Framework_TestCase
{
    protected $row;
    protected $related;
    protected $record;
    protected $zim;
    protected $irk;

    protected function setUp()
    {
        $this->zim = $this->createMock(RecordInterface::CLASS);
        $this->irk = $this->createMock(RecordSetInterface::CLASS);

        $this->row = new Row([
            'id' => '1',
            'foo' => 'bar',
            'baz' => 'dib',
        ]);

        $this->related = new Related([
            'zim' => $this->zim,
            'irk' => $this->irk,
        ]);

        $this->record = new Record('FakeMapper', $this->row, $this->related);
    }

    public function testGetRow()
    {
        $this->assertSame($this->row, $this->record->getRow());
    }

    public function testGetRelated()
    {
        $this->assertSame($this->related, $this->record->getRelated());
    }

    public function test__get()
    {
        // row
        $this->assertSame('bar', $this->record->foo);

        // related
        $this->assertSame($this->zim, $this->record->zim);

        // missing
        $this->setExpectedException(
            'Atlas\Orm\Exception',
            'Atlas\Orm\Mapper\Record::$noSuchField does not exist'
        );
        $this->record->noSuchField;
    }

    public function test__set()
    {
        // row
        $this->record->foo = 'barbar';
        $this->assertSame('barbar', $this->record->foo);
        $this->assertSame('barbar', $this->row->foo);

        // related
        $newZim = $this->createMock(RecordInterface::CLASS);
        $this->record->zim = $newZim;
        $this->assertSame($newZim, $this->record->zim);

        // missing
        $this->setExpectedException(
            'Atlas\Orm\Exception',
            'Atlas\Orm\Mapper\Record::$noSuchField does not exist'
        );
        $this->record->noSuchField = 'missing';
    }

    public function test__isset()
    {
        // row
        $this->assertTrue(isset($this->record->foo));

        // related
        $this->assertTrue(isset($this->record->zim));

        // missing
        $this->setExpectedException(
            'Atlas\Orm\Exception',
            'Atlas\Orm\Mapper\Record::$noSuchField does not exist'
        );
        isset($this->record->noSuchField);
    }

    public function test__unset()
    {
        // row
        unset($this->record->foo);
        $this->assertNull($this->record->foo);
        $this->assertNull($this->row->foo);

        // related
        unset($this->record->zim);
        $this->assertNull($this->record->zim);

        // missing
        $this->setExpectedException(
            'Atlas\Orm\Exception',
            'Atlas\Orm\Mapper\Record::$noSuchField does not exist'
        );
        unset($this->record->noSuchField);
    }

    public function testHas()
    {
        // row
        $this->assertTrue($this->record->has('foo'));

        // related
        $this->assertTrue($this->record->has('zim'));

        // missing
        $this->assertFalse($this->record->has('noSuchField'));
    }

    public function testSet()
    {
        $newZim = $this->createMock(RecordInterface::CLASS);
        $newZim->method('getArrayCopy')->willReturn('gir');

        $newIrk = $this->createMock(RecordSetInterface::CLASS);
        $newIrk->method('getArrayCopy')->willReturn('doom');

        $this->record->set([
            'foo' => 'hello',
            'zim' => $newZim,
            'irk' => $newIrk,
        ]);

        $actual = $this->record->getArrayCopy();
        $expected = [
            'id' => '1',
            'foo' => 'hello',
            'baz' => 'dib',
            'zim' => 'gir',
            'irk' => 'doom',
        ];
        $this->assertSame($expected, $actual);
    }
}
