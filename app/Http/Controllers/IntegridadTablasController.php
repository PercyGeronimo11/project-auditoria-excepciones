<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IntegridadTablasController extends Controller
{
    public function index(Request $request){
        $tableDataArray=session()->get('tablesName');
        $tableNames = array_keys($tableDataArray);

        $colForeignKeys = [];
        $tableNamesRefer =[];
        $columnNamesRefer =[];

        foreach($tableDataArray as $tableKey => $tableValueArray) {
            $fields = [];
            $tableNameReference=[];
            $colNameReference=[];

            foreach($tableValueArray["foreignKeys"] as $colForeignKey) {
                if (isset($colForeignKey->Field)) {
                    $fields[] = $colForeignKey->Field;
                } elseif (isset($colForeignKey->COLUMN_NAME)) {
                    $fields[] = $colForeignKey->COLUMN_NAME;
                }
                $tableNameReference=$colForeignKey->REFERENCED_TABLE_NAME;
                $colNameReference=$colForeignKey->REFERENCED_COLUMN_NAME;
            }
            $colForeignKeys[$tableKey] = $fields;
            $tableNamesRefer[$tableKey]= $tableNameReference;
            $columnNamesRefer[$tableKey]=$colNameReference;
        }

        //dd($tableDataArray, $tableNames,$fields,$tableNamesRefer[$tableKey],$columnNamesRefer[$tableKey]);
        return view('tablas.index',compact('tableNames','colForeignKeys','tableNamesRefer','columnNamesRefer')); 
    }

    public function analysis(Request $request){
        $tableDataArray= session()->get('tablesName');
        $tableNameSelect=$request->input('tabla');
        $keyForeignNameSelect=$request->input('claveForanea');
        $tableNameRefer="";
        $columnNameRefer="";

        //OBTENER DATOS DE LA REFERENCIA
        foreach($tableDataArray[$tableNameSelect]['foreignKeys'] as $tableValue){
            if ($tableValue->COLUMN_NAME === "venta_id") {
                $tableNameRefer = $tableValue->REFERENCED_TABLE_NAME;
                $columnNameRefer = $tableValue->REFERENCED_COLUMN_NAME;
            }
        }

        $tableDataSelect = $tableDataArray[$tableNameSelect];
        $tableDataRefer = $tableDataArray[$tableNameRefer];


        //CALCULO DE EXCEPCIONES
        $listExceptions=[];
        $numExcepciones=0;
        foreach($tableDataSelect['data'] as $registroSelect){
            $numCorrectos=0;
            $numIncorrectos=0;
            foreach($tableDataRefer['data'] as $registroRefer){
                if($registroSelect->$keyForeignNameSelect==$registroRefer->$columnNameRefer){
                    $numCorrectos++;
                }else{
                    $numIncorrectos++;
                }
            }
            if($numIncorrectos==count($tableDataRefer['data'])){
                $exceptionNotFound="La clave foranea no se encontro en la tabla referenciada";
                $listExceptions[$registroSelect->$keyForeignNameSelect]=$exceptionNotFound;
                $numExcepciones++;
            }else if($numCorrectos>1){
                $exceptionNotFound="La clave foranea se encuentra ".$numCorrectos." veces en la tabla referenciada";
                $listExceptions[$registroSelect->$keyForeignNameSelect]=$exceptionNotFound;
                $numExcepciones++;
            }elseif($numCorrectos==1){
                //NO EXISTE EXECPION
            }
        }
        
        //dd($tableNameSelect,$keyForeignNameSelect,$tableDataArray,$tableDataSelect,$numExcepciones);
        return view('tablas.indexReport',compact('listExceptions','numExcepciones')); 
    }
}

