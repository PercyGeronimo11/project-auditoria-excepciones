<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Database;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;

class DatabaseController extends Controller
{


    protected $connectionName = 'dynamic';
    protected $driver; // Propiedad para almacenar el driver


    public function __construct()
    {
        // Configurar la conexión a la base de datos en el constructor
        $this->configureDatabaseConnection();
        $this->driver = request()->input('driver');
    }


    public function showConnectionForm()
    {
        return view('conexion.connection_form');
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
        
            $database = new Database();
            $database->tipo =  $request->input('driver');
            $database->host = $request->input('host');
            $database->nombre_db = $request->input('database');
            $database->usuario = $request->input('username');
            $database->contraseña = $request->input('password');
            $database->estado = '1';
            $database->save();

        $connection = [
            'host' => $request->input('host'),
            'database' => $request->input('database'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        $driver = $request->input('driver');

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

        foreach ($tableNames as $tableName) {
            if ($driver == 'mysql') {
                $columns = DB::connection('dynamic')->select("SHOW COLUMNS FROM $tableName");
            } elseif ($driver == 'sqlsrv') {
                $columns = DB::connection('dynamic')
                    ->table('INFORMATION_SCHEMA.COLUMNS')
                    ->select('COLUMN_NAME')
                    ->where('TABLE_NAME', $tableName)
                    ->get()
                    ->pluck('COLUMN_NAME')
                    ->toArray();
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

    public function showTableMysql(Request $request, $tableName)
    {
        $driver = $this->driver;
        $columns = DB::connection('dynamic')->select("SHOW COLUMNS FROM $tableName");
        $tableData = DB::connection('dynamic')->table($tableName)->get();

        // $columns = DB::connection('dynamic')
        //     ->table('INFORMATION_SCHEMA.COLUMNS')
        //     ->select('TABLE_NAME', 'COLUMN_NAME', 'DATA_TYPE')
        //     ->get();
        // return  $columns ;
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



}
  