<?php

use PowerComponents\LivewirePowerGrid\DataSource\Support\Sql;

it('finds database driver name', function () {
    expect(Sql::getDatabaseDriverName())->not->toBeNull();
});

it('finds database version', function () {
    expect(Sql::getDatabaseVersion())->not->toBeNull();
});

it('returns the proper "LIKE" syntax', function () {
    $driver = Sql::getDatabaseDriverName();

    expect(Sql::like())
        ->when(
            $driver === 'mysql',
            fn ($syntax) => $syntax->toBe('LIKE')
        )
        ->when(
            $driver === 'sqlite',
            fn ($syntax) => $syntax->toBe('LIKE')
        )
        ->when(
            $driver === 'sqlsrv',
            fn ($syntax) => $syntax->toBe('LIKE')
        )
        ->when(
            $driver === 'pgsql',
            fn ($syntax) => $syntax->toBe('ILIKE')
        )
        ->not->toBeNull();
});

it('returns sortField', function (array $data) {
    expect(Sql::getSortSqlByDriver('field', $data['db'], $data['version']))
        ->toBe($data['expected']);
})->with([
    [['db' => 'sqlite', 'version' => '3.36.0',  'expected' => 'CAST(field AS INTEGER) {sortDirection}']],
    [['db' => 'mysql', 'version' => '5.5.59-MariaDB',  'expected' => 'field+0 {sortDirection}']],
    [['db' => 'mysql', 'version' => '5.4.1',  'expected' => 'field+0 {sortDirection}']],
    [['db' => 'mysql', 'version' => '5.7.36', 'expected' => 'field+0 {sortDirection}']],
    [['db' => 'mysql', 'version' => '8.0.3',  'expected' => 'field+0 {sortDirection}']],
    [['db' => 'mysql', 'version' => '8.0.4',  'expected' => "CAST(NULLIF(REGEXP_REPLACE(field, '[[:alpha:]]+', ''), '') AS SIGNED INTEGER) {sortDirection}"]],
    [['db' => 'mysql', 'version' => '8.0.5',  'expected' => "CAST(NULLIF(REGEXP_REPLACE(field, '[[:alpha:]]+', ''), '') AS SIGNED INTEGER) {sortDirection}"]],
    [['db' => 'pgsql', 'version' => '9.6.24',  'expected' => "CAST(NULLIF(REGEXP_REPLACE(field, '\D', '', 'g'), '') AS INTEGER) {sortDirection}"]],
    [['db' => 'pgsql', 'version' => '13.5',  'expected' => "CAST(NULLIF(REGEXP_REPLACE(field, '\D', '', 'g'), '') AS INTEGER) {sortDirection}"]],
    [['db' => 'pgsql', 'version' => '15.5',  'expected' => "CAST(NULLIF(REGEXP_REPLACE(field, '\D', '', 'g'), '') AS INTEGER) {sortDirection}"]],
    [['db' => 'sqlsrv', 'version' => '9.2',  'expected' => "CAST(SUBSTRING(field, PATINDEX('%[a-z]%', field), LEN(field)-PATINDEX('%[a-z]%', field)) AS INT) {sortDirection}"]],
    [['db' => 'sqlsrv', 'version' => '14.00.3421',  'expected' => "CAST(SUBSTRING(field, PATINDEX('%[a-z]%', field), LEN(field)-PATINDEX('%[a-z]%', field)) AS INT) {sortDirection}"]],
    [['db' => 'sqlsrv', 'version' => '20.80',  'expected' => "CAST(SUBSTRING(field, PATINDEX('%[a-z]%', field), LEN(field)-PATINDEX('%[a-z]%', field)) AS INT) {sortDirection}"]],
    [['db' => 'unsupported-db', 'version' => '29.00.00',  'expected' => 'field+0 {sortDirection}']],
]);
