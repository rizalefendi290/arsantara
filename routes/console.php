<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:migrate-mysql-to-pgsql
    {--fresh : Drop PostgreSQL tables before running migrations}
    {--skip-schema : Copy data without running Laravel migrations}
    {--source-host= : MySQL host override}
    {--source-port= : MySQL port override}
    {--source-database= : MySQL database override}
    {--source-username= : MySQL username override}
    {--source-password= : MySQL password override}
', function (): int {
    $source = [
        'driver' => 'mysql',
        'host' => $this->option('source-host') ?: env('MYSQL_LEGACY_HOST', '127.0.0.1'),
        'port' => $this->option('source-port') ?: env('MYSQL_LEGACY_PORT', '3306'),
        'database' => $this->option('source-database') ?: env('MYSQL_LEGACY_DATABASE', 'arsantaramanagement'),
        'username' => $this->option('source-username') ?: env('MYSQL_LEGACY_USERNAME', 'root'),
        'password' => $this->option('source-password') ?? env('MYSQL_LEGACY_PASSWORD', ''),
        'unix_socket' => env('MYSQL_LEGACY_SOCKET', ''),
        'charset' => env('MYSQL_LEGACY_CHARSET', 'utf8mb4'),
        'collation' => env('MYSQL_LEGACY_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'strict' => false,
    ];

    Config::set('database.connections.mysql_legacy', $source);
    DB::purge('mysql_legacy');

    $targetDatabase = config('database.connections.pgsql.database');
    arsantaraEnsurePostgresDatabaseExists($this, $targetDatabase);
    DB::purge('pgsql');

    if (! $this->option('skip-schema')) {
        $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';
        $this->call($command, ['--force' => true]);
    }

    DB::connection('mysql_legacy')->getPdo();
    DB::connection('pgsql')->getPdo();

    $sourceTables = collect(DB::connection('mysql_legacy')->select(
        'select table_name from information_schema.tables where table_schema = ? and table_type = ?',
        [$source['database'], 'BASE TABLE']
    ))->pluck('table_name')->map(fn ($table) => (string) $table);

    $targetTables = collect(DB::connection('pgsql')->select(
        "select table_name from information_schema.tables where table_schema = current_schema() and table_type = 'BASE TABLE'"
    ))->pluck('table_name')->map(fn ($table) => (string) $table);

    $knownOrder = collect([
        'users',
        'password_reset_tokens',
        'categories',
        'listings',
        'listing_images',
        'property_details',
        'car_details',
        'motorcycle_details',
        'favorites',
        'carousels',
        'posts',
        'post_images',
        'testimonials',
        'agent_requests',
        'upgrade_requests',
        'site_visits',
        'listing_views',
        'organization_members',
        'partners',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
    ]);

    $commonTables = $knownOrder
        ->merge($targetTables->diff($knownOrder))
        ->filter(fn ($table) => $table !== 'migrations')
        ->filter(fn ($table) => $sourceTables->contains($table) && $targetTables->contains($table))
        ->values();

    if ($commonTables->isEmpty()) {
        $this->warn('Tidak ada tabel yang sama antara MySQL lama dan PostgreSQL target.');

        return Command::FAILURE;
    }

    $quotedTables = $commonTables
        ->map(fn ($table) => '"'.str_replace('"', '""', $table).'"')
        ->implode(', ');

    DB::connection('pgsql')->statement('TRUNCATE TABLE '.$quotedTables.' RESTART IDENTITY CASCADE');

    $targetColumnTypes = arsantaraPostgresColumnTypes();

    foreach ($commonTables as $table) {
        $sourceColumns = collect(DB::connection('mysql_legacy')->select(
            'select column_name from information_schema.columns where table_schema = ? and table_name = ? order by ordinal_position',
            [$source['database'], $table]
        ))->pluck('column_name')->map(fn ($column) => (string) $column);

        $targetColumns = collect(DB::connection('pgsql')->select(
            'select column_name from information_schema.columns where table_schema = current_schema() and table_name = ? order by ordinal_position',
            [$table]
        ))->pluck('column_name')->map(fn ($column) => (string) $column);

        $columns = $targetColumns->intersect($sourceColumns)->values()->all();

        if ($columns === []) {
            $this->warn("Lewati {$table}: tidak ada kolom yang cocok.");
            continue;
        }

        $primaryKey = arsantaraMysqlPrimaryKey($source['database'], $table);
        $query = DB::connection('mysql_legacy')->table($table)->select($columns);

        if ($primaryKey !== null && in_array($primaryKey, $columns, true)) {
            $query->orderBy($primaryKey);
        }

        $copied = 0;
        $query->chunk(500, function ($rows) use ($table, $columns, $targetColumnTypes, &$copied): void {
            $payload = $rows->map(function ($row) use ($table, $columns, $targetColumnTypes) {
                $record = [];

                foreach ($columns as $column) {
                    $record[$column] = arsantaraNormalizeForPostgres(
                        $row->{$column},
                        $targetColumnTypes[$table][$column] ?? null
                    );
                }

                return $record;
            })->all();

            if ($payload !== []) {
                DB::connection('pgsql')->table($table)->insert($payload);
                $copied += count($payload);
            }
        });

        $this->line("{$table}: {$copied} baris");
    }

    arsantaraResetPostgresSequences($commonTables->all());
    $this->info('Migrasi data MySQL ke PostgreSQL selesai.');

    return Command::SUCCESS;
})->purpose('Create the PostgreSQL schema and copy data from the legacy MySQL database');

if (! function_exists('arsantaraEnsurePostgresDatabaseExists')) {
    function arsantaraEnsurePostgresDatabaseExists($console, string $database): void
    {
        $connection = config('database.connections.pgsql');
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=postgres;sslmode=%s',
            $connection['host'],
            $connection['port'],
            $connection['sslmode'] ?? 'prefer'
        );

        $pdo = new PDO($dsn, $connection['username'], $connection['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $exists = $pdo->prepare('select 1 from pg_database where datname = ?');
        $exists->execute([$database]);

        if ($exists->fetchColumn()) {
            $console->line("Database PostgreSQL {$database} sudah ada.");
            return;
        }

        $quotedDatabase = '"'.str_replace('"', '""', $database).'"';
        $pdo->exec('create database '.$quotedDatabase);
        $console->info("Database PostgreSQL {$database} dibuat.");
    }
}

if (! function_exists('arsantaraPostgresColumnTypes')) {
    function arsantaraPostgresColumnTypes(): array
    {
        $types = [];

        foreach (DB::connection('pgsql')->select(
            'select table_name, column_name, data_type from information_schema.columns where table_schema = current_schema()'
        ) as $column) {
            $types[(string) $column->table_name][(string) $column->column_name] = (string) $column->data_type;
        }

        return $types;
    }
}

if (! function_exists('arsantaraMysqlPrimaryKey')) {
    function arsantaraMysqlPrimaryKey(string $database, string $table): ?string
    {
        $primaryKey = DB::connection('mysql_legacy')->selectOne(
            'select column_name from information_schema.key_column_usage where table_schema = ? and table_name = ? and constraint_name = ? order by ordinal_position limit 1',
            [$database, $table, 'PRIMARY']
        );

        return $primaryKey ? (string) $primaryKey->column_name : null;
    }
}

if (! function_exists('arsantaraNormalizeForPostgres')) {
    function arsantaraNormalizeForPostgres($value, ?string $targetType)
    {
        if ($value === null) {
            return null;
        }

        if ($targetType === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? (bool) $value;
        }

        if (
            in_array($targetType, ['date', 'timestamp without time zone', 'timestamp with time zone'], true)
            && in_array($value, ['0000-00-00', '0000-00-00 00:00:00'], true)
        ) {
            return null;
        }

        return $value;
    }
}

if (! function_exists('arsantaraResetPostgresSequences')) {
    function arsantaraResetPostgresSequences(array $tables): void
    {
        foreach ($tables as $table) {
            $idColumn = DB::connection('pgsql')->selectOne(
                'select column_default from information_schema.columns where table_schema = current_schema() and table_name = ? and column_name = ?',
                [$table, 'id']
            );

            if ($idColumn && str_starts_with((string) $idColumn->column_default, 'nextval(')) {
                $escapedTable = str_replace("'", "''", $table);
                $quotedTable = '"'.str_replace('"', '""', $table).'"';
                DB::connection('pgsql')->statement(
                    "select setval(pg_get_serial_sequence('{$escapedTable}', 'id'), coalesce((select max(id) from {$quotedTable}), 1), (select max(id) from {$quotedTable}) is not null)"
                );
            }
        }
    }
}
