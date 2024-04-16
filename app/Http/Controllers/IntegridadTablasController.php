<?php

namespace App\Http\Controllers;

use App\Models\TablaIntegridad;
use Exception;
use Illuminate\Http\Request;
use App\Models\Database;
use Dompdf\Dompdf;

class IntegridadTablasController extends Controller
{
    public function index()
    {
        $database = Database::latest()->first(); 
        $nombre=$database->nombre_db;
        $integridades=TablaIntegridad::where('estado','<>','0')
        ->where('name_bd','=',$nombre)
        ->get();
        
        return view('tablas.index', compact('integridades'));
    }

    public function create(Request $request)
    {
        $tableDataArray = session()->get('tablesName');
        $tableNames = array_keys($tableDataArray);
    
        $colForeignKeys = [];
        $colPrimaryKeys = [];
        
        foreach ($tableNames as $tableName) {
            $tableData = $tableDataArray[$tableName]; 
            $primaryKeys = [];
            $foreignKeys = [];
    
            foreach ($tableData['columns'] as $column) {
                // dd( $tableDataArray ,$tableNames,$tableDataArray[$tableName],$tableData['columns']);
                if ($column->Key == 'PRI') {
                    $primaryKeys[] = $column->Field; 
                }
                // if ($column->Key == 'MUL') {
                //     $foreignKeys[] = $column->Field; 
                // }    
            }

            foreach ($tableData['columns'] as $column) {
                // dd( $tableDataArray ,$tableNames,$tableDataArray[$tableName],$tableData['columns']);
                // if ($column->Key == 'PRI') {
                //     $primaryKeys[] = $column->Field; 
                // }
                if ($column->Key == 'MUL') {
                    $foreignKeys[] = $column->Field; 
                }    
            }
    
            $colPrimaryKeys[$tableName] = $primaryKeys;
            $colForeignKeys[$tableName] = $foreignKeys;
        }
    
        return view('tablas.create', compact('tableNames', 'colForeignKeys', 'colPrimaryKeys'));
    }
    


    
    public function store(Request $request)
    {
        $nameTabla = $request->input('nameTabla');
        $nameKeyForanea = $request->input('nameClaveForanea');
        $nameTablaRef = $request->input('nameTablaRef');
        $nameKeyPrimary = $request->input('nameClavePrimary');

        $tableDataArray = session()->get('tablesName');

        foreach ($tableDataArray[$nameTabla]['columns'] as $clave) {
            if ($clave->Field == $nameKeyForanea) {
                $tipoDato_FK = $clave->Type;
            }
        }

        foreach ($tableDataArray[$nameTablaRef]["columns"] as $clave) {
            if ($clave->Field == $nameKeyPrimary) {
                $tipoDato_PK = $clave->Type;
            }
        }

        if (!$this->isCompatible($tipoDato_FK, $tipoDato_PK)) {
            $message = "La selección que desea evaluar no se puede realizar por incompatibilidad de tipo de datos";
            return redirect()->route('integridadtablas.create')->with('message', $message);
        }

        try {
            $tableFind = TablaIntegridad::where('table', $nameTabla)
                ->where('column_foreignkey', $nameKeyForanea)
                ->where('table_refer', $nameTablaRef)
                ->where('column_primarykey', $nameKeyPrimary)
                ->where('estado', 1)
                ->first();

            if ($tableFind) {
                $mensaje = "La Integridad que desa analizar, Ya existe en la lista";
                return redirect()->route('integridadtablas.index')->with('warning', $mensaje);
            } else {
                $table = new TablaIntegridad();
                $table->table = $nameTabla;
                $table->column_foreignkey = $nameKeyForanea;
                $table->table_refer = $nameTablaRef;
                $table->column_primarykey = $nameKeyPrimary;
                $table->estado = 1;
                $database = Database::latest()->first(); 
                $table->name_bd=$database->nombre_db;
                $table->name_bd=$database->nombre_db;

                if($table->save()){
                    $mensaje = "Se guardo exitosamente";
                    return redirect()->route('integridadtablas.index')->with('success', $mensaje);
                }else{
                    $mensaje = "Ocurrio un problema al guardar";
                    return redirect()->route('integridadtablas.create')->with('success', $mensaje);
                }

                
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function exportarPdf(Request $request)
    {
        $database = Database::latest()->first();
        // $data= TablaIntegridad::create([
        //     'bdManager' => $database->tipo,
        //     'dbName' => $database->nombre_db,
        //     'tableName' => $tabla, 
        //     'field' => $campo, 
        //     'sequenceType' => $tipo_secuencia,
        //     'sequenceOrder' => $orden_secuencia, 
        //     'increment' => $incremento,
        //     'state' => 1,
        //     'user' => Auth::user()->email
        // ]);

        $listExceptions = json_decode($request->input('listExceptions'), true);
        $numExcepciones = $request->input('numExcepciones');
        $tableNameSelect = $request->input('tableNameSelect');
        $tableRefNameSelect = $request->input('tableRefNameSelect');
    
        $countListExceptions = is_array($listExceptions) ? count($listExceptions) : 0;
    
        $html = view('tablas.reportpdf', compact('listExceptions', 'numExcepciones','tableNameSelect','tableRefNameSelect',))->render();
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->stream('report.pdf');
    }

  

    // public function store(Request $request)
    // {
    //     try {
    //         $tableFind=TablaIntegridad::where('table',$request->input('nameTabla'))
    //         ->where('column_foreignkey',$request->input('nameClaveForanea'))
    //         ->where('table_refer',$request->input('nameTablaRef'))
    //         ->where('column_primarykey', $request->input('nameClavePrimary'))
    //         ->where('estado',1)
    //         ->first();

    //         if($tableFind){
    //             $mensaje="La Integridad que desa analizar, Ya existe en la lista";
    //             return redirect()->route('integridadtablas.index')->with('warning', $mensaje);
    //         }else{
    //             $table = new TablaIntegridad();
    //             $table->table = $request->input('nameTabla');
    //             $table->column_foreignkey = $request->input('nameClaveForanea');
    //             $table->table_refer = $request->input('nameTablaRef');
    //             $table->column_primarykey = $request->input('nameClavePrimary');
    //             $table->estado=1;
    //             $table->fecha=date("Y-m-d");
    //             $table->save();
    //             $mensaje="Se guardo exitosamente";
    //             return redirect()->route('integridadtablas.index')->with('success', $mensaje);
    //         }

    //     } catch (Exception $ex) {
    //         return $ex;
    //     }
    // }


    private function isCompatible($tipoDatoFK, $tipoDatoPK)
    {
        $listaTipos = [
            "numerico" => ["SMALLINT","smallint", "INT","int", "MEDIUMINT","mediumint", "BIGINT","bigint", "TINYINT","tinyint"],
            "letras" => ["CHAR","char", "VARCHAR","char" ,"TEXT", "text","MEDIUMTEXT","mediumtext", "TINYTEXT","tinyint"],
            "fechas" => ["DATE", "date","DATETIME","datetime"]
        ];

        $grupoFK = $this->getGrupoTipoDato($tipoDatoFK, $listaTipos);
        $grupoPK = $this->getGrupoTipoDato($tipoDatoPK, $listaTipos);
        return $grupoFK !== null && $grupoPK !== null && $grupoFK === $grupoPK;
    }

    private function getGrupoTipoDato($tipoDato, $listaTipos)
    {
        foreach ($listaTipos as $grupo => $listTipos) {
            //dd($listaTipos,$tipoDato);
            if (in_array($tipoDato, $listTipos)) {
                return $grupo;
            }
        }
        return null;
    }


    public function analysis(Request $request, $id)
    {
        $integridad=TablaIntegridad::find($id);
        $tableDataArray = session()->get('tablesName');
        $tableNameSelect = $integridad->table;
        $keyForeignNameSelect = $integridad->column_foreignkey;

        $tableRefNameSelect = $integridad->table_refer;
        $keyPrimaryNameSelect = $integridad->column_primarykey;

        // $tableNameSelect = $request->input('nameTabla');
        // $keyForeignNameSelect = $request->input('nameClaveForanea');

        // $tableRefNameSelect = $request->input('nameTablaRef');
        // $keyPrimaryNameSelect = $request->input('nameClavePrimary');


        $tableDataSelect = $tableDataArray[$tableNameSelect];
        $tableDataRefer = $tableDataArray[$tableRefNameSelect];


        //CALCULO DE EXCEPCIONES
        $listExceptions = [];
        $numExcepciones = 0;

        foreach ($tableDataSelect['data'] as $registroSelect) {
            $numCorrectos = 0;
            $numIncorrectos = 0;

            if (($registroSelect->$keyForeignNameSelect) != null &&
                ($registroSelect->$keyForeignNameSelect) != "NULL"
            ) {
                //dd($registroSelect,$registroSelect->$keyForeignNameSelect);
                foreach ($tableDataRefer['data'] as $registroRefer) {
                    if ($registroSelect->$keyForeignNameSelect == $registroRefer->$keyPrimaryNameSelect) {
                        $numCorrectos++;
                    } else {
                        $numIncorrectos++;
                    }
                }
                if ($numIncorrectos == count($tableDataRefer['data'])) {
                    $exceptionNotFound = "La clave foranea no se encontro en la tabla referenciada";
                    $listExceptions[$registroSelect->$keyForeignNameSelect] = $exceptionNotFound;
                    $numExcepciones++;
                }
            } else {
                //dd($registroSelect,$registroSelect->$keyForeignNameSelect);         
                $exceptionNotNull = "La Clave foranea es NULL";
                $listExceptions["NULL-" . $numExcepciones] = $exceptionNotNull;
                $numExcepciones++;
            }
        }

        return view('tablas.show', compact('listExceptions', 'tableNameSelect', 'tableRefNameSelect', 'numExcepciones'));
    }

    public function cancelar()
    {
        return redirect()->route('integridadtablas.create');
    }


    // public function create_anterior(Request $request)
    // {
    //     $tableDataArray = session()->get('tablesName');
    //     $tableNames = array_keys($tableDataArray);

    //     //dd($tableDataArray);
    //     $colForeignKeys = [];
    //     $tableNamesRefer = [];
    //     $columnNamesRefer = [];

    //     foreach ($tableDataArray as $tableKey => $tableValueArray) {
    //         $fields = [];
    //         $tableNameReference = [];
    //         $colNameReference = [];

    //         foreach ($tableValueArray["foreignKeys"] as $colForeignKey) {
    //             if (isset($colForeignKey->Field)) {
    //                 $fields[] = $colForeignKey->Field;
    //             } elseif (isset($colForeignKey->COLUMN_NAME)) {
    //                 $fields[] = $colForeignKey->COLUMN_NAME;
    //             }
    //             //$tableNameReference=$colForeignKey->REFERENCED_TABLE_NAME;
    //             //$colNameReference=$colForeignKey->REFERENCED_COLUMN_NAME;
    //         }
    //         $colForeignKeys[$tableKey] = $fields;
    //         //$tableNamesRefer[$tableKey]= $tableNameReference;
    //         //$columnNamesRefer[$tableKey]=$colNameReference;
    //     }

    //     //dd($tableDataArray, $tableNames,$fields,$tableNamesRefer[$tableKey],$columnNamesRefer[$tableKey]);
    //     return view('tablas.create', compact('tableNames', 'colForeignKeys', 'tableNamesRefer', 'columnNamesRefer'));
    // }

    public function delete($id)
    {
        $integridad=TablaIntegridad::find($id);
        $integridad->estado=0;
        $integridad->save();
        return redirect()->route('integridadtablas.index');
    }
}
