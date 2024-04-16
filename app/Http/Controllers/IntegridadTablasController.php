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
        // $nameTable = $request->input('nameTable');
        // $nameTableRef = $request->input('nameTableRef');

        $countExceptionsNull=0;
        $messageExceptionsNull="";
        foreach($listExceptions as $item){
            if($item['type']=='ExceptionNull'){
                $countExceptionsNull++;
                if($countExceptionsNull==1){
                    $messageExceptionsNull="Los Soguientes Registros de la tabla ". $integridad->table. "que son: ";
                }
                $messageExceptionsNull.=$item["keyPrimaryTable"].",";
            }
        }
        if ($countExceptionsNull > 0) {
            $messageExceptionsNull = rtrim($messageExceptionsNull, ', ') . ". Sus claves foraneas se encontraron como nulas.";
        }
        //$messageExceptionsNull.=" Sus claves foraneas se encontro que son NULOS";

        $countExceptionsNotFound=0;
        $condicionExceptionNotFound="Luego de analizar la tabla ".Str::upper( $integridad->table);
        $criterioExceptionNotFound=" Las normas COBIT 2019, en el apartado DS5.3.1 - Mantener integridad de datos, recomienda implementar medidas para asegurar la exactitud, consistencia y confiabilidad de los datos, incluyendo las tablas de la base de datos";
        foreach($listExceptions as $item){
            if($item['type']=='ExceptionNotFound'){
                $countExceptionsNotFound++;
                if($countExceptionsNotFound==1){
                    $condicionExceptionNotFound.=" se a detectado anomalias en siguientes registros juntos con sus claves foraneas que son los siguientes: [{$integridad->column_primarykey} , {$integridad->column_foreignkey}]".
                    " [{$item['keyPrimaryTable']} , {$item['keyForeignTable']}], ";
                }
                $condicionExceptionNotFound.= " [{$item['keyPrimaryTable']} , {$item['keyForeignTable']}], ";
            }
        }
        if ($countExceptionsNotFound > 0) {
            $condicionExceptionNotFound = rtrim($condicionExceptionNotFound, ', ') . ". Todas estas claves Foraneas no fueron encontrados en la tabla referencial ".Str::upper( $integridad->table_refer);
        }

        $criterioExceptionNotFound=" Las normas COBIT 2019, en el apartado DS5.3.1 - Mantener integridad de datos, recomienda implementar medidas para asegurar la exactitud, consistencia y confiabilidad de los datos, incluyendo las tablas de la base de datos. ";

        $efectoExceptionNotFound="Errores en la ejecución de consultas y aplicaciones que dependen de la base de datos";
        $causaExceptionNotFound="Errores en el diseño de la base de datos, por ejemplo, claves foráneas mal definidas o inconsistentes, 
        Errores en la inserción o actualización de datos, por ejemplo, ingresar valores incorrectos en las claves foráneas o 
        Eliminación incorrecta de registros en la tabla referenciada sin actualizar las referencias en la tabla hija.";
        
        $recomendacionExceptionNotFound="Revisar cuidadosamente la definición de las relaciones entre las tablas ".Str::upper($integridad->table)." y ".Str::upper( $integridad->table_refer).", y corregir cualquier error en la definición de las claves foráneas. \n 
        Para cada registro afectado en la tabla ".Str::upper($integridad->table).", verificar la validez de la clave foránea y actualizarla si es necesario. Si la categoría correspondiente no existe, crear un nuevo registro en la tabla ".Str::upper( $integridad->table_refer)." o asignar el producto a una categoría existente válida.";

        $resultadosExceptionNotFound=[
            'Condicion'=>$condicionExceptionNotFound,
            'Criterio' =>$criterioExceptionNotFound,
            'Efecto'=> $efectoExceptionNotFound,
            'Causa'=> $causaExceptionNotFound,
            'Recomendacion'=>$recomendacionExceptionNotFound
        ];


        $countListExceptions = is_array($listExceptions) ? count($listExceptions) : 0;

        $html = view('tablas.reportpdf', compact('listExceptions','messageExceptionsNull', 'resultadosExceptionNotFound','numExcepciones',  'integridad'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->stream('report.pdf');
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
        $keyPrimaryTable="";

        $nameTableRef = $integridad->table_refer;
        $keyPrimaryTableRef = $integridad->column_primarykey;

        $tableDataSelect = $tableDataArray[$nameTable];
        $tableDataRefer = $tableDataArray[$nameTableRef];

        foreach($tableDataSelect['columns'] as $column){
            if($column->Key=='PRI'){
                $keyPrimaryTable=$column->Field;
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
                        'type'=>"ExceptionNotFound",
                        'keyPrimaryTable'=>$registroSelect->$keyPrimaryTable,
                        'keyForeignTable' => $registroSelect->$keyForeignTable,
                        'message' => $exceptionNotFound
                    ];
                    $numExcepciones++;
                }
            } else {
                //dd($registroSelect,$registroSelect->$keyForeignTable); 
                $listExceptions[$numExcepciones] = [
                    'type'=>"ExceptionNull",
                    'keyPrimaryTable'=>$registroSelect->$keyPrimaryTable,
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
