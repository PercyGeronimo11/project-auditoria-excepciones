<?php

namespace App\Http\Controllers;

use App\Models\TablaIntegridad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Time;

class IntegridadTablasController extends Controller
{
    public function index()
    {
        $integridades=TablaIntegridad::where('estado','<>','0')->get();
        
        return view('tablas.index', compact('integridades'));
    }

    public function create(Request $request)
    {
        $tableDataArray = session()->get('tablesName');
        $tableNames = array_keys($tableDataArray);

        //dd($tableDataArray);
        $colForeignKeys = [];
        $colPrimaryKeys = [];

        foreach ($tableDataArray as $tableKey => $tableValueArray) {
            $fields = [];
            $fieldsPrimary = [];

            //para claves foraneas
            foreach ($tableValueArray["foreignKeys"] as $colForeignKey) {
                if (isset($colForeignKey->Field)) {
                    $fields[] = $colForeignKey->Field;
                } elseif (isset($colForeignKey->COLUMN_NAME)) {
                    $fields[] = $colForeignKey->COLUMN_NAME;
                }
            }
            $colForeignKeys[$tableKey] = $fields;

            // Para claves primarias
            foreach ($tableValueArray["primaryKeys"] as $colprimaryKey) {
                if (isset($colprimaryKey->Field)) {
                    $fieldsPrimary[] = $colprimaryKey->Field;
                } elseif (isset($colprimaryKey->COLUMN_NAME)) {
                    $fieldsPrimary[] = $colprimaryKey->COLUMN_NAME;
                }
            }
            $colPrimaryKeys[$tableKey] = $fieldsPrimary;
        }

        //dd($tableDataArray, $tableNames,$fields,$tableNamesRefer[$tableKey],$columnNamesRefer[$tableKey]);
        return view('tablas.create', compact('tableNames', 'colForeignKeys', 'colPrimaryKeys'));
    }


    public function store(Request $request)
    {
        try {
            $tableFind=TablaIntegridad::where('table',$request->input('nameTabla'))
            ->where('column_foreignkey',$request->input('nameClaveForanea'))
            ->where('table_refer',$request->input('nameTablaRef'))
            ->where('column_primarykey', $request->input('nameClavePrimary'))
            ->where('estado',1)
            ->first();


            if($tableFind){
                $mensaje="La Integridad que desa analizar, Ya existe en la lista";
                return redirect()->route('integridadtablas.index')->with('warning', $mensaje);
            }else{
                $table = new TablaIntegridad();
                $table->table = $request->input('nameTabla');
                $table->column_foreignkey = $request->input('nameClaveForanea');
                $table->table_refer = $request->input('nameTablaRef');
                $table->column_primarykey = $request->input('nameClavePrimary');
                $table->estado=1;
                $table->fecha=date("Y-m-d");
                $table->save();
                $mensaje="Se guardo exitosamente";
                return redirect()->route('integridadtablas.index')->with('success', $mensaje);
            }

        } catch (Exception $ex) {
            return $ex;
        }
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


    public function create_anterior(Request $request)
    {
        $tableDataArray = session()->get('tablesName');
        $tableNames = array_keys($tableDataArray);

        //dd($tableDataArray);
        $colForeignKeys = [];
        $tableNamesRefer = [];
        $columnNamesRefer = [];

        foreach ($tableDataArray as $tableKey => $tableValueArray) {
            $fields = [];
            $tableNameReference = [];
            $colNameReference = [];

            foreach ($tableValueArray["foreignKeys"] as $colForeignKey) {
                if (isset($colForeignKey->Field)) {
                    $fields[] = $colForeignKey->Field;
                } elseif (isset($colForeignKey->COLUMN_NAME)) {
                    $fields[] = $colForeignKey->COLUMN_NAME;
                }
                //$tableNameReference=$colForeignKey->REFERENCED_TABLE_NAME;
                //$colNameReference=$colForeignKey->REFERENCED_COLUMN_NAME;
            }
            $colForeignKeys[$tableKey] = $fields;
            //$tableNamesRefer[$tableKey]= $tableNameReference;
            //$columnNamesRefer[$tableKey]=$colNameReference;
        }

        //dd($tableDataArray, $tableNames,$fields,$tableNamesRefer[$tableKey],$columnNamesRefer[$tableKey]);
        return view('tablas.create', compact('tableNames', 'colForeignKeys', 'tableNamesRefer', 'columnNamesRefer'));
    }

    public function delete($id)
    {
        $integridad=TablaIntegridad::find($id);
        $integridad->estado=0;
        $integridad->save();
        return redirect()->route('integridadtablas.index');
    }
}
