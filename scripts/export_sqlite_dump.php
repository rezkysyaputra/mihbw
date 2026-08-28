<?php

$input = __DIR__ . '/../database/database.sqlite';
$output = __DIR__ . '/../database/database.sql';

if (! file_exists($input)) {
    fwrite(STDERR, "SQLite file not found: {$input}\n");
    exit(1);
}

$pdo = new PDO('sqlite:' . $input);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$quote = static function ($value): string {
    if ($value === null) {
        return 'NULL';
    }

    if (is_bool($value)) {
        return $value ? '1' : '0';
    }

    if (is_int($value) || is_float($value)) {
        return (string) $value;
    }

    return "'" . str_replace("'", "''", (string) $value) . "'";
};

$lines = [];
$lines[] = '-- SQLite dump generated from database/database.sqlite';
$lines[] = 'PRAGMA foreign_keys=OFF;';
$lines[] = 'BEGIN TRANSACTION;';
$lines[] = '';

$tables = $pdo->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")->fetchAll();

foreach ($tables as $table) {
    $tableName = $table['name'];
    $createSql = trim((string) $table['sql']);

    if ($createSql !== '') {
        $lines[] = $createSql . ';';
    }

    $rows = $pdo->query('SELECT * FROM "' . str_replace('"', '""', $tableName) . '"')->fetchAll();

    foreach ($rows as $row) {
        $columns = array_map(static fn ($column) => '"' . str_replace('"', '""', $column) . '"', array_keys($row));
        $values = array_map($quote, array_values($row));
        $lines[] = 'INSERT INTO "' . str_replace('"', '""', $tableName) . '" (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ');';
    }

    $lines[] = '';
}

$lines[] = 'COMMIT;';

file_put_contents($output, implode(PHP_EOL, $lines) . PHP_EOL);

echo "Exported to {$output}\n";
