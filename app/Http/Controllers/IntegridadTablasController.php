<?php

namespace App\Http\Controllers;

use App\Models\TablaIntegridad;
use Exception;
use Illuminate\Http\Request;
use App\Models\Database;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class IntegridadTablasController extends Controller
{
    public function index()
    {
        $database = Database::latest()->first();
        $nombre = $database->nombre_db;
        $integridades = TablaIntegridad::where('estado', '<>', '0')
            ->where('name_bd', '=', $nombre)
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
        // dd("paso create",$request);

        return view('tablas.create', compact('tableNames', 'colForeignKeys', 'colPrimaryKeys'));
    }




    public function store(Request $request)
    {
        //dd("No se pudo evaluar",$request);
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
            dd("No se pudo evaluar", $tipoDato_FK, $tipoDato_PK);
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
                $database = Database::latest()->first();
                $table = new TablaIntegridad([
                    'table' => $nameTabla,
                    'column_foreignkey' => $nameKeyForanea,
                    'table_refer' => $nameTablaRef,
                    'column_primarykey' => $nameKeyPrimary,
                    'estado' => 1,
                    'name_bd' => $database->nombre_db,
                    'type_db' => $database->tipo,
                    'user' => Auth::user()->email
                ]);
                $table->save();

                $mensaje = "Se guardó exitosamente";
                return redirect()->route('integridadtablas.index')->with('success', $mensaje);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function exportarPdf(Request $request, $id)
    {
        $integridad = TablaIntegridad::find($id);
        $listExceptions = json_decode($request->input('listExceptions'), true);
        $numExcepciones = $request->input('numExcepciones');

        $tipoNotFound = "ExceptionNotFound";
        $tipoNull = "ExceptionNull";

        $resultadosExceptionNotFound = $this->getResultadosExceptionsNotFound($listExceptions, $integridad, $tipoNotFound);
        $resultadosExceptionNull = $this->getResultadosExceptionsNull($listExceptions, $integridad, $tipoNull);

        $countListExceptions = is_array($listExceptions) ? count($listExceptions) : 0;

        if($countListExceptions>0){
            $html = view('tablas.reportpdf', compact('listExceptions', 'resultadosExceptionNull', 'resultadosExceptionNotFound', 'numExcepciones',  'integridad'))->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();
            return $dompdf->stream('report.pdf');
        }else{
            $mensaje = "No Hay ninguna excpeción que mostrar";
            return redirect()->route('integridadtablas.index')->with('success', $mensaje);
        }

    }

    private function getResultadosExceptionsNull($listExceptions, $integridad, $tipoNull)
    {
        $countExceptionsNull = 0;
        $clavesPrimariasString = "";
        $resultadosExceptionNull = [];
        foreach ($listExceptions as $item) {
            if ($item['type'] == $tipoNull) {
                $countExceptionsNull++;
                $clavesPrimariasString .= $item['keyPrimaryTable'] . ", ";
            }
        }

        if ($countExceptionsNull > 0) {

            $condicionException = "Se ha detectado que los registros " . $clavesPrimariasString . "  de la tabla " . Str::upper($integridad->table) . " tienen las claves foránea " . Str::upper($integridad->column_foreignkey) . " en valor NULL.  Esto significa que  " . Str::upper($integridad->table) . " no están asociados a ninguna  " . Str::upper($integridad->table_refer);

            $criterioException = "La norma ISO/IEC 27001 exige que las organizaciones implementen controles para garantizar la integridad de los datos, incluyendo la integridad referencial de las bases de datos.

            La integridad referencial se refiere a la relación entre dos tablas en una base de datos, donde una tabla (tabla hija) contiene una clave foránea que debe coincidir con una clave primaria en otra tabla (tabla padre).

            En este caso, la tabla " . Str::upper($integridad->table) . " es la tabla hija y la tabla " . Str::upper($integridad->table_refer) . " es la tabla padre. La clave foránea " . Str::upper($integridad->column_foreignkey) . " en la tabla " . Str::upper($integridad->table) . " debe coincidir con la clave primaria " . Str::upper($integridad->column_primarykey) . "en la tabla  " . Str::upper($integridad->table_refer);

            $efectoException = "Incoherencias en los datos: Los registros " . $clavesPrimariasString . " de la tabla " . Str::upper($integridad->table) . ". Esto significa que la información sobre  " . Str::upper($integridad->table_refer) . " de " . Str::upper($integridad->table) . " es incompleta o incorrecta. 

            Errores en consultas y aplicaciones: Al realizar consultas tanto en la tabla " . Str::upper($integridad->table) . " como la tabla " . Str::upper($integridad->table_refer) . " es posible que se obtengan resultados incorrectos.";

            $causaException = "Errores en la inserción o actualización de datos: Es posible que se hayan ingresado valores NULL en la clave foránea " . Str::upper($integridad->column_foreignkey) . " de la tabla  " . Str::upper($integridad->table) . " durante la inserción o actualización de registros. Esto puede deberse a errores humanos o a fallos en el software de aplicación.
            Errores en el diseño de la base de datos: Es posible que la definición de la clave foránea " . Str::upper($integridad->column_foreignkey) . "  en la tabla " . Str::upper($integridad->table) . " permita valores NULL";

            $recomendacionException= "Revisar el diseño de la base de datos: Verificar si la definición de la clave foránea " . Str::upper($integridad->column_foreignkey) . " en la tabla " . Str::upper($integridad->table) . "permite valores NULL. Si es así, modificar la definición para que no se permita este tipo de valores. \n 
            Identificar y corregir los errores en los datos: Revisar los registros ". $clavesPrimariasString." de la tabla " . Str::upper($integridad->table) . " y determinar " . Str::upper($integridad->table_refer) . "  correcta para cada " . Str::upper($integridad->table);

            $resultadosExceptionNull = [
                'Condicion' => $condicionException,
                'Criterio' => $criterioException,
                'Efecto' => $efectoException,
                'Causa' => $causaException,
                'Recomendacion' => $recomendacionException
            ];
        }
        return $resultadosExceptionNull;
    }


    private function getResultadosExceptionsNotFound($listExceptions, $integridad, $tipoNotFound)
    {
        $countExceptionsNotFound = 0;
        $clavesForaneasString = "";
        $clavesPrimariasString = "";
        $resultadosExceptionNotFound = [];
        foreach ($listExceptions as $item) {
            if ($item['type'] == $tipoNotFound) {
                $countExceptionsNotFound++;
                $clavesPrimariasString .= $item['keyPrimaryTable'] . ", ";
                $clavesForaneasString .= $item['keyForeignTable'] . ", ";
            }
        }

        if ($countExceptionsNotFound > 0) {
            $condicionExceptionNotFound = "Luego de analizar la tabla " . Str::upper($integridad->table) . "se a detectado anomalias en siguientes registros: " . $clavesPrimariasString . " juntos con sus claves foraneas: " . $clavesForaneasString . " respectivamente .Todas estas claves Foraneas no fueron encontrados en la tabla referencial " . Str::upper($integridad->table_refer);

            $criterioExceptionNotFound = "La norma ISO/IEC 27001 exige que las organizaciones implementen controles para garantizar la integridad de los datos, incluyendo la integridad referencial de las bases de datos.

            La integridad referencial se refiere a la relación entre dos tablas en una base de datos, donde una tabla (tabla hija) contiene una clave foránea que debe coincidir con una clave primaria en otra tabla (tabla padre).
            
            En este caso, la tabla " . Str::upper($integridad->table) . " es la tabla hija y la tabla " . Str::upper($integridad->table_refer) . " es la tabla padre. La clave foránea " . Str::upper($integridad->column_foreignkey) . " en la tabla " . Str::upper($integridad->table) . " debe coincidir con la clave primaria " . Str::upper($integridad->column_primarykey) . "en la tabla  " . Str::upper($integridad->table_refer);


            $efectoExceptionNotFound = "Incoherencias en los datos: Los registros " . $clavesPrimariasString . " de la tabla " . Str::upper($integridad->table) . " hacen referencia a datos que no existen en la tabla " . Str::upper($integridad->table_refer) . ". 
            Errores en consultas y aplicaciones: Al realizar consultas tanto en la tabla " . Str::upper($integridad->table) . " como la tabla " . Str::upper($integridad->table_refer) . " o utilizar aplicaciones que dependen de la base de datos, es posible que se obtengan resultados incorrectos.";

            $causaExceptionNotFound = "Errores en el diseño de la base de datos: Es posible que la relación entre las tablas " . Str::upper($integridad->table) . " y " . Str::upper($integridad->table_refer) . " no esté correctamente definida. 
            Eliminación incorrecta de registros: Es posible que se hayan eliminado registros de la tabla " . Str::upper($integridad->table_refer) . " sin actualizar las referencias en la tabla " . Str::upper($integridad->table);

            $recomendacionExceptionNotFound = "Revisar cuidadosamente la definición de las relaciones entre las tablas " . Str::upper($integridad->table) . " y " . Str::upper($integridad->table_refer) . ", y corregir cualquier error en la definición de las claves foráneas. \n 
            Actualizar los registros afectados: Para cada registro afectado en la tabla " . Str::upper($integridad->table) . ", verificar la validez de la clave foránea y actualizarla si es necesario. Si un registro referenciado no existe, crear un nuevo registro en la tabla " . Str::upper($integridad->table_refer) . " de tal manera que sea valida.";

            $resultadosExceptionNotFound = [
                'Condicion' => $condicionExceptionNotFound,
                'Criterio' => $criterioExceptionNotFound,
                'Efecto' => $efectoExceptionNotFound,
                'Causa' => $causaExceptionNotFound,
                'Recomendacion' => $recomendacionExceptionNotFound
            ];
        }

        return $resultadosExceptionNotFound;
    }






    private function isCompatible($tipoDatoFK, $tipoDatoPK)
    {
        $listaTipos = [
            "numerico" => ["SMALLINT", "smallint", "INT", "int", "MEDIUMINT", "mediumint", "BIGINT", "bigint", "TINYINT", "tinyint"],
            "letras" => ["CHAR", "char", "VARCHAR", "char", "TEXT", "text", "MEDIUMTEXT", "mediumtext", "TINYTEXT", "tinyint"],
            "fechas" => ["DATE", "date", "DATETIME", "datetime"]
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
        $integridad = TablaIntegridad::find($id);
        $tableDataArray = session()->get('tablesName');
        $nameTable = $integridad->table;
        $keyForeignTable = $integridad->column_foreignkey;
        $keyPrimaryTable = "";

        $nameTableRef = $integridad->table_refer;
        $keyPrimaryTableRef = $integridad->column_primarykey;

        $tableDataSelect = $tableDataArray[$nameTable];
        $tableDataRefer = $tableDataArray[$nameTableRef];

        foreach ($tableDataSelect['columns'] as $column) {
            if ($column->Key == 'PRI') {
                $keyPrimaryTable = $column->Field;
                break;
            }
        }

        //Excepciones:
        $exceptionNotFound = "La clave foranea no se encontro en la tabla referenciada";
        $exceptionNotNull = "La Clave foranea es NULL";

        //Criterios
        $keyForanea = 0;
        $condicion = "la Clave foranea " . $keyForanea . " No existe en las claves primaria de " . $keyPrimaryTableRef . " de la tabla Referenciada " . $nameTableRef;
        $criterio = "La clave fornaea " . $keyForanea . " Debe existe en las claves primaria de " . $keyPrimaryTableRef . " de la tabla Referenciada " . $nameTableRef . "Para cumplir con la Integridad de tablas";

        //CALCULO DE EXCEPCIONES
        $listExceptions = [];
        $numExcepciones = 0;

        foreach ($tableDataSelect['data'] as $registroSelect) {
            $numCorrectos = 0;
            $numIncorrectos = 0;

            if (($registroSelect->$keyForeignTable) != null &&
                ($registroSelect->$keyForeignTable) != "NULL"
            ) {
                //dd($registroSelect,$registroSelect->$keyForeignTable);
                foreach ($tableDataRefer['data'] as $registroRefer) {
                    if ($registroSelect->$keyForeignTable == $registroRefer->$keyPrimaryTableRef) {
                        $numCorrectos++;
                    } else {
                        $numIncorrectos++;
                    }
                }
                if ($numIncorrectos == count($tableDataRefer['data'])) {
                    //dd($listExceptions, $registroRefer); 
                    $listExceptions[$numExcepciones] = [
                        'type' => "ExceptionNotFound",
                        'keyPrimaryTable' => $registroSelect->$keyPrimaryTable,
                        'keyForeignTable' => $registroSelect->$keyForeignTable,
                        'message' => $exceptionNotFound
                    ];
                    $numExcepciones++;
                }
            } else {
                //dd($registroSelect,$registroSelect->$keyForeignTable); 
                $listExceptions[$numExcepciones] = [
                    'type' => "ExceptionNull",
                    'keyPrimaryTable' => $registroSelect->$keyPrimaryTable,
                    'keyForeignTable' => "NULL",
                    'message' => $exceptionNotNull
                ];
                $numExcepciones++;
            }
        }


        //dd($listExceptions); 
        return view('tablas.show', compact('listExceptions', 'nameTable', 'nameTableRef', 'numExcepciones', 'integridad'));
    }


    public function cancelar()
    {
        return redirect()->route('integridadtablas.create');
    }


    public function delete($id)
    {
        $integridad = TablaIntegridad::find($id);
        $integridad->estado = 0;
        $integridad->save();
        return redirect()->route('integridadtablas.index');
    }
}



// public function analysis_ant(Request $request, $id)
// {
//     $integridad = TablaIntegridad::find($id);
//     $tableDataArray = session()->get('tablesName');
//     $nameTable = $integridad->table;
//     $keyForeignTable = $integridad->column_foreignkey;
//     $nameTableRef = $integridad->table_refer;
//     $keyPrimaryTableRef = $integridad->column_primarykey;

//     $tableDataSelect = $tableDataArray[$nameTable];
//     $tableDataRefer = $tableDataArray[$nameTableRef];


//     //Excepciones:
//     $exceptionNotFound = "La clave foranea no se encontro en la tabla referenciada";
//     $exceptionNotNull = "La Clave foranea es NULL";

//     //Criterios
//     $keyForanea = 0;
//     $condicion = "la Clave foranea " . $keyForanea . " No existe en las claves primaria de " . $keyPrimaryTableRef . " de la tabla Referenciada " . $nameTableRef;
//     $criterio = "La clave fornaea " . $keyForanea . " Debe existe en las claves primaria de " . $keyPrimaryTableRef . " de la tabla Referenciada " . $nameTableRef . "Para cumplir con la Integridad de tablas";

//     //CALCULO DE EXCEPCIONES
//     $listExceptions = [];
//     $numExcepciones = 0;

//     foreach ($tableDataSelect['data'] as $registroSelect) {
//         $numCorrectos = 0;
//         $numIncorrectos = 0;

//         if (($registroSelect->$keyForeignTable) != null &&
//             ($registroSelect->$keyForeignTable) != "NULL"
//         ) {
//             //dd($registroSelect,$registroSelect->$keyForeignTable);
//             foreach ($tableDataRefer['data'] as $registroRefer) {
//                 if ($registroSelect->$keyForeignTable == $registroRefer->$keyPrimaryTableRef) {
//                     $numCorrectos++;
//                 } else {
//                     $numIncorrectos++;
//                 }
//             }
//             if ($numIncorrectos == count($tableDataRefer['data'])) {
//                 $listExceptions[$registroSelect->$keyForeignTable] = $exceptionNotFound;
//                 $numExcepciones++;
//             }
//         } else {
//             //dd($registroSelect,$registroSelect->$keyForeignTable);         
//             $listExceptions["NULL-" . $numExcepciones] = $exceptionNotNull;
//             $numExcepciones++;
//         }
//     }

//     return view('tablas.show', compact('listExceptions', 'nameTable', 'nameTableRef', 'numExcepciones', 'integridad'));
// }

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
