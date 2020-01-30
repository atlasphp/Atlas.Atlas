# CHANGELOG

This is the changelog for the 2.x series.

## 2.8.0

This release backports a feature from the 3.x series, such that when the
TableEvents::beforeInsert() or TableEvents::beforeUpdate() methods return an
array, that array is used for the insert or update. This allows finer control
over, among other things, the logic that determines differences from the initial
Row data.

Expanded testing to include PHP 7.2 and 7.3.

## 2.7.0

This release adds support for 'manual' transactions outside a unit of work,
via Atlas::beginTransaction(), commit(), and rollBack() methods.

Also, Row::isEquivalent() now compares booleans as integers, so that a change
from 1 => true or 0 => false (and vice versa) is no longer considered a
difference. This should help reduce "Expected 1 row affected, actual 0" errors
with some databases (notably MySQL).

## 2.6.0

This release introduces an AtlasBuilder similar to the one in the 3.x series,
thereby allowing you to lazy-load mappers instead of having to register them
in advance. Using AtlasBuilder is now preferred over AtlasContainer, though
of course the latter continues to work as before. Documentation and tests have
been updated accordingly.

## 2.5.0

This release exposes underlying profiler functionality in Aura.Sql
ConnectionLocator via two new methods: Atlas::setProfiling() and
Atlas::getProfiles().

It also incorporates two performance enhancements: one to
AbstractMapper::newRelated() via a prototype object for relateds, and one to
AbstractTable::newRow() via array_intersect_key() comparison.

## 2.4.0

This release fixes a bug where MapperEvents::modifySelect() was not being
honored by various AbstractMapper::fetch() methods. Two new off-interface
methods, AbstractTable::selectRow() and selectRows(), are introduced as a
result.

## 2.3.0

This release adds one "off-interface" method, `AbstractTable::getIdentityMap()`,
to support retrieval of initial values on rows.

It also fixes a bug where a relationship definition could use the same name more
than once, silently overwriting the previous definition.

Finally, it includes updated documentation and tests.

## 2.2.0

This release adds two "off-interface" events, `TableEvents::modifySelect()` and
`MapperEvents::modifySelect()`, to allow modification of the `TableSelect` and
`MapperSelect` query objects.

These events are added only to the implementation classes, and not the
interfaces, to make the functionality available without introducing a BC break.
A future major revision may incorporate them into the relevant interfaces.

It also fixes the bug noted at <https://github.com/atlasphp/Atlas.Orm/issues/86>
where existing connections are not updated to the current transaction state.

## 2.1.0

This release adds support for many-to-one relationships by reference (aka
"polymorphic association") in addition to some convenience and informational
methods.

- Documentation and code hygiene fixes

- Add method `Mapper\Related::getFields()`

- Add method `Mapper\RecordSet::removeAll()`

- Add method `Mapper\RecordSet::markForDeletion()`

- Add method `Relationship\Relationships::manyToOneByReference()`

- Add method `Mapper\AbstractMapper::manyToOneByReference()`

## 2.0.0

Documentation changes and updates.

## 2.0.0-beta1

MOTIVATION:

In 1.x, executing a Transaction::persist() will not work properly when the
relationships are mapped across connections that are not the same as the main
record. This is because the Transaction begins on the main record connection,
but does not have access to the connections for related records, and so cannot
track them. The only way to fix this is to introduce a BC break on the Table and
Transaction classes, both their constructors and their internal operations.

As long as BC breaks are on the table, this creates the opportunity to make
other changes, though with an eye to minimizing those changes to reduce the
hassle of moving from 1.x to 2.x.

UPGRADE NOTES FROM 1.x:

- This package now requires PHP 7.1 or later, and PHPUnit 6.x for development.
  Non-strict typehints have been added throughout, except in cases where they
  might break classes generated from 1.x.

- You *should not* need to modify any classes generated from 1.x; however, if
  you have overridden class methods in custom classes, you *may* need to modify
  that code to add typehints.

- This package continues to use Aura.Sql and Aura.SqlQuery 2.x; you *should not*
  need to change any queries.

- You *should not* need to change any calls to AtlasContainer for setup.

- The following methods now return `null` (instead of `false`) when they fail.
  You may need to change any logic checking for a strict `false` return value;
  checking for a loosely false-ish value will continue to work.

    - AbstractMapper::fetchRecord()
    - AbstractMapper::fetchRecordBy()
    - AbstractTable::fetchRow()
    - Atlas::fetchRecord()
    - Atlas::fetchRecordBy()
    - IdentityMap::getRow()
    - MapperInterface::fetchRecord()
    - MapperInterface::fetchRecordBy()
    - MapperSelect::fetchRecord()
    - RecordSet::getOneBy()
    - RecordSet::removeOneBy()
    - RecordSetInterface::getOneBy()
    - RecordSetInterface::removeOneBy()
    - Table::updateRowPerform()
    - TableInterface::fetchRow()
    - TableSelect::fetchOne()
    - TableSelect::fetchRow()

  (N.b.: Values for a single *related* record are still `false`, not `null`.
  That is, `null` still indicates "there was no attempt to fetch a related
  record," while `false` still indicates "there was an attempt to fetch a
  related record, but it did not exist.")

- The following methods will now *always* return a RecordSetInterface, even when
  no records are found. (Previously, they would return an empty array when no
  records were found.) To check for "no records found", call `isEmpty()` on the
  returned RecordSetInterface.

    - AbstractMapper::fetchRecordSet()
    - AbstractMapper::fetchRecordSetBy()
    - Atlas::fetchRecordSet()
    - Atlas::fetchRecordSetBy()
    - MapperInterface::fetchRecordSet()
    - MapperInterface::fetchRecordSetBy()
    - MapperSelect::fetchRecordSet()

OTHER CHANGES FROM 1.x:

- Added Atlas\Orm\Table\ConnectionManager to manage connections at a table-
  specific level.

    - Manages simultaneous transactions over multiple connections.

    - Allows setting of table-specific "read" and "write" connections.

    - Allows on-the-fly replacement of "read" connections with "write"
      connections while writing (useful for synchronizing reads with writes
      while in a transaction) or always (useful for GET-after-POST situations).

    - If the ConnectionManager starts a transaction on *one* connection (whether
      read or write) then it will start a tranasaction on *all* connections as
      they are retrieved.

- AbstractTable now uses the ConnectionManager instead of Aura.Sql
  ConnectionLocator, and *does not* retain (memoize) the connection objects.
  It retrieves them from the ConnectionManager each time they are needed; this
  helps maintain transaction state across multiple connections.

- Modified Transaction class to use the ConnectionManager, instead of tracking
  write connections on its own. This makes sure AbstractMapper::persist() will
  work properly with different related connections inside a transaction.

- The ManyToMany relationship now honors the order of the returned rows.

- Updated docs and tests.
