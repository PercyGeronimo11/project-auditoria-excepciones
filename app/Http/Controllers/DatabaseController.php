<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Database;

class DatabaseController extends Controller
{
    protected $connectionName = 'dynamic';
    protected $driver; // Propiedad para almacenar el driver

    public function __construct()
    {
        $this->configureDatabaseConnection();
        $latestDatabase = Database::latest()->first();
        $this->driver = $latestDatabase ? $latestDatabase->tipo : null;
    }


    public function showConnectionForm()
    {
        $databases = Database::all(); // Obtener todas las bases de datos
        return view('conexion.connection_form', compact('databases'));
    }

    protected function configureDatabaseConnection()
    {
        $database = Database::latest()->first(); // Obtener el último registro de la tabla Database

        if ($database) {
            config(['database.connections.' . $this->connectionName => [
                'driver' => $database->tipo,
                'host' => $database->host,
                'port' => $database->puerto,
                'database' => $database->nombre_db,
                'username' => $database->usuario,
                'password' => $database->contraseña,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ]]);
        }
    }

    public function connectDatabase(Request $request)
    {

        $driver = $request->input('driver');

        $databaseName = $request->input('database');

        $existingDatabase = Database::where('nombre_db', $databaseName)->exists();

        $connection = [
            'host' => $request->input('host'),
            'database' => $request->input('database'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        try {

            if ($driver == 'mysql') {
                config(['database.connections.dynamic' => array_merge([
                    'driver' => 'mysql',
                    'port' => 3306,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                ], $connection)]);
            } elseif ($driver == 'sqlsrv') {
                config(['database.connections.dynamic' => array_merge([
                    'driver' => 'sqlsrv',
                    'port' => 1433,
                ], $connection)]);
            }
            DB::connection('dynamic')->getPdo();
            if (!$existingDatabase) {
                $database = new Database();
                $database->tipo =  $request->input('driver');
                $database->host = $request->input('host');
                $database->nombre_db = $request->input('database');
                $database->usuario = $request->input('username');
                $database->contraseña = $request->input('password');
                $database->estado = '1';
                $database->save();
            } else {
                Database::where('nombre_db', $databaseName)->delete();
                $database = new Database();
                $database->tipo =  $request->input('driver');
                $database->host = $request->input('host');
                $database->nombre_db = $request->input('database');
                $database->usuario = $request->input('username');
                $database->contraseña = $request->input('password');
                $database->estado = '1';
                $database->save();
            }
        } catch (\Exception $e) {
            $errorMessage = "Error al conectar a la base de datos: " . $e->getMessage();
            return back()->withError($errorMessage)->withInput();
        }

        $tables = [];

        if ($driver == 'mysql') {
            $tables = DB::connection('dynamic')->select('SHOW TABLES');
            $tableNames = array_map('current', json_decode(json_encode($tables), true));
        } elseif ($driver == 'sqlsrv') {
            $tableNames = DB::connection('dynamic')
                ->table('INFORMATION_SCHEMA.TABLES')
                ->select('TABLE_NAME')
                ->where('TABLE_TYPE', 'BASE TABLE')
                ->get()
                ->pluck('TABLE_NAME')
                ->toArray();
        }

        $tablesData = [];
        $columnTypes = [];

        foreach ($tableNames as $tableName) {
            if ($driver == 'mysql') {
                $columns = DB::connection('dynamic')->select("SHOW COLUMNS FROM $tableName");
                $nombreColumna = DB::connection('dynamic')->getSchemaBuilder()->getColumnListing($tableName);

                $database = Database::latest()->first(); 
                $tipoColumna = $types = DB::connection('dynamic')
                ->table('INFORMATION_SCHEMA.COLUMNS')
                ->where('TABLE_SCHEMA', '=', $database->nombre_db)
                ->where('TABLE_NAME', '=', $tableName)
                ->pluck('COLUMN_TYPE');


                $foreignKeys = DB::connection('dynamic')->select("
                    SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = '$tableName' AND CONSTRAINT_NAME <> 'PRIMARY'
                ");
                foreach ($columns as $column) {
                    $columnTypes[$column->Field] = $column->Type;
                    if ($column->Key == 'PRI') {
                        $primaryKeys[] = $column->Field;
                    }
                }

            } elseif ($driver == 'sqlsrv') {
                $columns = DB::connection('dynamic')
                    ->table('INFORMATION_SCHEMA.COLUMNS')
                    ->select('COLUMN_NAME', 'DATA_TYPE') // Agregar la selección del tipo de dato
                    ->where('TABLE_NAME', $tableName)
                    ->get()
                    ->map(function ($column) {
                        return [
                            'name' => $column->COLUMN_NAME,
                            'type' => $column->DATA_TYPE // Agregar el tipo de dato al array resultante
                        ];
                    })->toArray();


                    $tipoColumna = DB::connection('dynamic')
                    ->table('INFORMATION_SCHEMA.COLUMNS')
                    ->select('COLUMN_NAME', 'DATA_TYPE') 
                    ->where('TABLE_NAME', '=', $tableName)
                    ->pluck('COLUMN_TYPE');
                    $nombreColumna = DB::connection('dynamic')
                    ->table('INFORMATION_SCHEMA.COLUMNS')
                    ->select('COLUMN_NAME', 'DATA_TYPE') 
                    ->where('TABLE_NAME', '=', $tableName)
                    ->pluck('COLUMN_NAME');
            }

            $tableData = DB::connection('dynamic')->table($tableName)->take(20000)->get();

            $tablesData[$tableName] = [
                'columns' => $columns,
                'data' => $tableData
            ];
            $columnas[$tableName] = $nombreColumna;
            $tipos[$tableName] = $tipoColumna;
            
        }

        session()->put('driverBD', $driver);
        session()->put('tablesName', $tablesData);
        session()->put('columnas', $columnas);
        session()->put('tipos', $tipos);


        return view('conexion.database_info', compact('tablesData', 'driver'));
    }

    public function showTableMysql(Request $request, $tableName)
    {
        $driver = $this->driver;
        $columns = DB::connection('dynamic')->select("SHOW COLUMNS FROM $tableName");
        $tableData = DB::connection('dynamic')->table($tableName)->get();
        return view('conexion.show_tableMysql', compact('tableName', 'columns', 'tableData', 'driver'));
    }


    public function showTableSQL(Request $request, $tableName)
    {
        $driver = $this->driver;
        $columns = DB::connection('dynamic')
            ->table('INFORMATION_SCHEMA.COLUMNS')
            ->select('COLUMN_NAME')
            ->where('TABLE_NAME', $tableName)
            ->get();
        $tableData = DB::connection('dynamic')->table($tableName)->get();

        return view('conexion.show_tableSQL', compact('tableName', 'columns', 'tableData', 'driver'));
    }

    public function listDatabases()
    {
        $databases = DB::table('database')->get();
        return view('conexion.connection_form', compact('databases'));
    }

    public function eliminarRegistro($id)
    {
       $database = Database::findOrFail($id);
       $nombre = $database->nombre_db; 
       $database->delete(); 

       return back()->with('success', 'Se eliminó la base de datos "' . $nombre . '".');
    }


    public function showAllTables()
    {

        $driver = $this->driver;
        
        if ($driver == 'mysql') {
            $tables = DB::connection('dynamic')->select('SHOW TABLES');
            $tableNames = array_map('current', json_decode(json_encode($tables), true));
        } elseif ($driver == 'sqlsrv') {
            $tableNames = DB::connection('dynamic')
                ->table('INFORMATION_SCHEMA.TABLES')
                ->select('TABLE_NAME')
                ->where('TABLE_TYPE', 'BASE TABLE')
                ->get()
                ->pluck('TABLE_NAME')
                ->toArray();
        }

        $tablesData = [];


        foreach ($tableNames as $tableName) {
            if ($driver == 'mysql') {
                $columns = DB::connection('dynamic')->select("SHOW COLUMNS FROM $tableName");
                $foreignKeys = DB::connection('dynamic')->select("
                    SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = '$tableName'
                ");
            } elseif ($driver == 'sqlsrv') {
                $columns = DB::connection('dynamic')
                    ->table('INFORMATION_SCHEMA.COLUMNS')
                    ->select('COLUMN_NAME', 'DATA_TYPE') // Agregar la selección del tipo de dato
                    ->where('TABLE_NAME', $tableName)
                    ->get()
                    ->map(function ($column) {
                        return [
                            'name' => $column->COLUMN_NAME,
                            'type' => $column->DATA_TYPE // Agregar el tipo de dato al array resultante
                        ];
                    })->toArray();
            }

        $tableData = DB::connection('dynamic')->table($tableName)->get();
            
        $tablesData[$tableName] = [
            'columns' => $columns,
            'data' => $tableData
        ];
        }

        session()->put('driverBD', $driver);
        session()->put('tablesName', $tablesData);

        return view('conexion.database_info', compact('tablesData', 'driver'));
    }
    
    public function showTableStructure($tableName)
    {
        $driver = $this->driver;
        $columnTypes = [];
        $primaryKey = [];
        $foreignKeys = [];
    
        if ($driver == 'mysql') {
            $columns = DB::connection('dynamic')->select("SHOW COLUMNS FROM $tableName");
    
            foreach ($columns as $column) {
                $columnTypes[$column->Field] = $column->Type;
                if ($column->Key == 'PRI') {
                    $primaryKey[] = $column->Field;
                }
            }
    
            $foreignKeys = DB::connection('dynamic')->select("
                SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = '$tableName' AND CONSTRAINT_NAME <> 'PRIMARY'
            ");
        } elseif ($driver == 'sqlsrv') {
            $columns = DB::connection('dynamic')
                ->table('INFORMATION_SCHEMA.COLUMNS')
                ->select('COLUMN_NAME', 'DATA_TYPE') // Agregar la selección del tipo de dato
                ->where('TABLE_NAME', $tableName)
                ->get();
    
            foreach ($columns as $column) {
                $columnTypes[$column->COLUMN_NAME] = $column->DATA_TYPE;
            }
    
            $primaryKeys = DB::connection('dynamic')
                ->table('INFORMATION_SCHEMA.TABLE_CONSTRAINTS')
                ->join('INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE', function ($join) use ($tableName) {
                    $join->on('INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_NAME', '=', 'INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.CONSTRAINT_NAME')
                        ->where('INFORMATION_SCHEMA.TABLE_CONSTRAINTS.TABLE_NAME', '=', $tableName)
                        ->where('INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_TYPE', '=', 'PRIMARY KEY');
                })
                ->select('INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.COLUMN_NAME')
                ->get();
    
            foreach ($primaryKeys as $primaryKeyColumn) {
                $primaryKey[] = $primaryKeyColumn->COLUMN_NAME;
            }
    
            $foreignKeys = DB::connection('dynamic')
            ->table('INFORMATION_SCHEMA.TABLE_CONSTRAINTS')
            ->join('INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE', function ($join) use ($tableName) {
                $join->on('INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_NAME', '=', 'INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.CONSTRAINT_NAME')
                    ->where('INFORMATION_SCHEMA.TABLE_CONSTRAINTS.TABLE_NAME', '=', $tableName)
                    ->where('INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_TYPE', '=', 'FOREIGN KEY');
            })
            ->select('INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.COLUMN_NAME', 'INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.TABLE_NAME')
            ->get();
        
        
        }
    
        return view('conexion.table_structure', compact('tableName', 'columns', 'columnTypes', 'primaryKey', 'foreignKeys', 'driver'));
    }
    

}
