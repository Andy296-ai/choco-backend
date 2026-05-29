<?php

namespace App\Http\Controllers\Panels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class DirectorDatabaseController extends Controller
{
    /**
     * Белый список: только эти таблицы доступны директору.
     * Пользователи, клиенты, сессии, кэш и системные таблицы скрыты.
     */
    private const ALLOWED_TABLES = [
        'absences',
        'bookings',
        'contacts',
        'portfolio_items',
        'salons',
        'schedules',
        'services',
    ];

    private function getTableNames(): array
    {
        return self::ALLOWED_TABLES;
    }

    public function index()
    {
        $tableNames = $this->getTableNames();

        return view('panels.director.db.index', compact('tableNames'));
    }

    public function showTable(string $table, Request $request)
    {
        if (!$this->isValidTableName($table)) {
            abort(404, 'Table not found');
        }

        $columns = $this->getTableColumns($table);
        $search = $request->get('search', '');
        $sortColumn = $request->get('sort', null);
        $sortDirection = $request->get('direction', 'asc');

        $query = DB::table($table);

        if ($search) {
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $column) {
                    if (!in_array($column['type'], ['blob', 'longblob', 'binary', 'varbinary']) &&
                        !in_array($column['type'], ['int', 'bigint', 'tinyint', 'smallint', 'mediumint', 'decimal', 'float', 'double'])
                    ) {
                        $q->orWhere($column['name'], 'LIKE', "%{$search}%");
                    } elseif (in_array($column['type'], ['int', 'bigint', 'tinyint', 'smallint', 'mediumint']) && is_numeric($search)) {
                        $q->orWhere($column['name'], '=', $search);
                    }
                }
            });
        }

        if ($sortColumn && in_array($sortColumn, array_column($columns, 'name'))) {
            $query->orderBy($sortColumn, $sortDirection);
        } else {
            $query->orderBy($columns[0]['name'], 'asc');
        }

        $rows = $query->paginate(20)->withQueryString();
        $tableNames = $this->getTableNames();

        return view('panels.director.db.table', compact(
            'table',
            'columns',
            'rows',
            'search',
            'sortColumn',
            'sortDirection',
            'tableNames'
        ));
    }

    public function create(string $table)
    {
        if (!$this->isValidTableName($table)) {
            abort(404, 'Table not found');
        }

        $columns = $this->getTableColumns($table);

        $formColumns = array_filter($columns, function ($column) {
            return !$column['auto_increment']
                && !in_array($column['name'], ['created_at', 'updated_at', 'deleted_at']);
        });

        return view('panels.director.db.create', compact('table', 'formColumns'));
    }

    public function store(string $table, Request $request)
    {
        if (!$this->isValidTableName($table)) {
            abort(404, 'Table not found');
        }

        $columns = $this->getTableColumns($table);

        $rules = [];
        $formColumns = array_filter($columns, function ($column) {
            return !$column['auto_increment']
                && !in_array($column['name'], ['created_at', 'updated_at', 'deleted_at']);
        });

        foreach ($formColumns as $column) {
            $rule = [];

            if ($column['nullable'] === false && $column['default'] === null) {
                $rule[] = 'required';
            }

            if (strpos($column['type'], 'int') !== false) {
                $rule[] = 'integer';
            } elseif (strpos($column['type'], 'decimal') !== false ||
                strpos($column['type'], 'float') !== false ||
                strpos($column['type'], 'double') !== false
            ) {
                $rule[] = 'numeric';
            } elseif (strpos($column['type'], 'date') !== false) {
                $rule[] = 'date';
            } elseif (strpos($column['type'], 'datetime') !== false || strpos($column['type'], 'timestamp') !== false) {
                $rule[] = 'date';
            } elseif (strpos($column['type'], 'email') !== false || $column['name'] === 'email') {
                $rule[] = 'email';
            }

            if ($column['max_length']) {
                $rule[] = 'max:' . $column['max_length'];
            }

            $rules[$column['name']] = implode('|', $rule);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->route('director.db.create', $table)
                ->withErrors($validator)
                ->withInput();
        }

        $data = [];
        foreach ($formColumns as $column) {
            $value = $request->input($column['name']);

            if ($value === null && $column['nullable']) {
                $data[$column['name']] = null;
            } elseif ($value !== null) {
                $data[$column['name']] = $value;
            }
        }

        if (Schema::hasColumn($table, 'created_at') && Schema::hasColumn($table, 'updated_at')) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        try {
            DB::table($table)->insert($data);

            return redirect()
                ->route('director.db.table', $table)
                ->with('success', 'Запись успешно создана.');
        } catch (\Exception $e) {
            return redirect()
                ->route('director.db.create', $table)
                ->with('error', 'Ошибка при создании записи: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(string $table, int $id)
    {
        if (!$this->isValidTableName($table)) {
            abort(404, 'Table not found');
        }

        $columns = $this->getTableColumns($table);
        $primaryKey = $this->getPrimaryKey($table);

        $row = DB::table($table)->where($primaryKey, $id)->first();

        if (!$row) {
            abort(404, 'Row not found');
        }

        $formColumns = array_filter($columns, function ($column) {
            return !in_array($column['name'], ['created_at', 'updated_at', 'deleted_at']);
        });

        return view('panels.director.db.edit', compact('table', 'formColumns', 'row', 'id', 'primaryKey'));
    }

    public function update(string $table, int $id, Request $request)
    {
        if (!$this->isValidTableName($table)) {
            abort(404, 'Table not found');
        }

        $columns = $this->getTableColumns($table);
        $primaryKey = $this->getPrimaryKey($table);

        $row = DB::table($table)->where($primaryKey, $id)->first();
        if (!$row) {
            abort(404, 'Row not found');
        }

        $rules = [];
        $formColumns = array_filter($columns, function ($column) use ($primaryKey) {
            return $column['name'] !== $primaryKey
                && !in_array($column['name'], ['created_at', 'updated_at', 'deleted_at']);
        });

        foreach ($formColumns as $column) {
            $rule = [];

            if ($column['nullable'] === false && $column['default'] === null) {
                $rule[] = 'required';
            }

            if (strpos($column['type'], 'int') !== false) {
                $rule[] = 'integer';
            } elseif (strpos($column['type'], 'decimal') !== false ||
                strpos($column['type'], 'float') !== false ||
                strpos($column['type'], 'double') !== false
            ) {
                $rule[] = 'numeric';
            } elseif (strpos($column['type'], 'date') !== false) {
                $rule[] = 'date';
            } elseif (strpos($column['type'], 'datetime') !== false || strpos($column['type'], 'timestamp') !== false) {
                $rule[] = 'date';
            } elseif (strpos($column['type'], 'email') !== false || $column['name'] === 'email') {
                $rule[] = 'email';
            }

            if ($column['max_length']) {
                $rule[] = 'max:' . $column['max_length'];
            }

            $rules[$column['name']] = implode('|', $rule);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()
                ->route('director.db.edit', [$table, $id])
                ->withErrors($validator)
                ->withInput();
        }

        $data = [];
        foreach ($formColumns as $column) {
            $value = $request->input($column['name']);

            if ($value === null && $column['nullable']) {
                $data[$column['name']] = null;
            } elseif ($value !== null) {
                $data[$column['name']] = $value;
            }
        }

        if (Schema::hasColumn($table, 'updated_at')) {
            $data['updated_at'] = now();
        }

        try {
            DB::table($table)->where($primaryKey, $id)->update($data);

            return redirect()
                ->route('director.db.table', $table)
                ->with('success', 'Запись успешно обновлена.');
        } catch (\Exception $e) {
            return redirect()
                ->route('director.db.edit', [$table, $id])
                ->with('error', 'Ошибка при обновлении записи: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $table, int $id)
    {
        if (!$this->isValidTableName($table)) {
            abort(404, 'Table not found');
        }

        $primaryKey = $this->getPrimaryKey($table);

        try {
            DB::table($table)->where($primaryKey, $id)->delete();

            return redirect()
                ->route('director.db.table', $table)
                ->with('success', 'Запись удалена.');
        } catch (\Exception $e) {
            return redirect()
                ->route('director.db.table', $table)
                ->with('error', 'Ошибка при удалении записи: ' . $e->getMessage());
        }
    }

    private function getTableColumns(string $table): array
    {
        // Schema::getColumns() works with SQLite, MySQL, PostgreSQL
        $columns = Schema::getColumns($table);
        $primaryKeys = Schema::getIndexes($table);

        // Build set of primary key column names
        $pkColumns = [];
        foreach ($primaryKeys as $index) {
            if ($index['primary']) {
                $pkColumns = array_merge($pkColumns, $index['columns']);
            }
        }

        return array_map(function ($column) use ($pkColumns) {
            $typeName = strtolower($column['type_name'] ?? $column['type'] ?? 'string');

            return [
                'name'          => $column['name'],
                'type'          => $typeName,
                'nullable'      => (bool) ($column['nullable'] ?? true),
                'default'       => $column['default'] ?? null,
                'max_length'    => $column['length'] ?? null,
                'primary'       => in_array($column['name'], $pkColumns),
                'auto_increment' => (bool) ($column['auto_increment'] ?? false),
            ];
        }, $columns);
    }

    private function getPrimaryKey(string $table): string
    {
        $columns = $this->getTableColumns($table);
        $primaryKeyColumn = array_filter($columns, function ($column) {
            return $column['primary'];
        });

        if (count($primaryKeyColumn) > 0) {
            return reset($primaryKeyColumn)['name'];
        }

        return 'id';
    }

    private function isValidTableName(string $table): bool
    {
        return in_array($table, self::ALLOWED_TABLES);
    }
}

