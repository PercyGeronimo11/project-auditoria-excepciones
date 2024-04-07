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

        //dd($tableDataArray, $fields,$tableNamesRefer[$tableKey],$columnNamesRefer[$tableKey]);
        return view('tablas.index',compact('tableNames','colForeignKeys','tableNamesRefer','columnNamesRefer')); 
    }

    public function analysis(Request $request){
        $tabla=$request->input('tabla');
        $claveForanea=$request->input('claveForanea');

        $tableDataArray= session()->get('tablesName');
        $tableDataSelect = $tableDataArray[$tabla];
        $tableDataReference = $tableDataArray[$tabla];

        dd($tableDataSelect);
       
        return view('tablas.index',compact('tableNames','colForeignKeys')); 
    }
}
