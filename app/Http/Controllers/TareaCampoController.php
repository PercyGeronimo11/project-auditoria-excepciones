<?php

namespace App\Http\Controllers;
use App\Models\TareaCampo;
use App\Models\Database;
use App\Http\Controllers\DatabaseController;
use Illuminate\Support\Facades\DB;
// use App\Models\TareaCampo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TareaCampoController extends Controller
{
    const PAGINATION=7;

    public function index(Request $request){
        $database = Database::latest()->first(); // Obtener el último registro de la tabla Database
        $nombre=$database->nombre_db;
        // return $nombre;
        $busqueda=$request->get('buscarpor');
        $TareaCampos=TareaCampo::where('tabla','like','%'.$busqueda.'%')
        ->where('estado','<>','0')
        ->where('baseDatos','=',$nombre)
        ->paginate($this::PAGINATION);
        // return session()->get('TareaCampos');
        return view('campo.index',compact('TareaCampos','busqueda'));
        // $tableNames = array_keys(session()->get('tablesName'));

        // $contenido=session()->get('tablesName');
        // // return  $contenido;
        // $columnas = [];
        // $columnas1 = [];
        // $tipos = [];
        // // return $contenido;
        // // return $columns;
        // // Recorrer cada tabla en el objeto
        // foreach($contenido as $tableName => $tableData) {
        //     // Crear un array para almacenar los nombres de los campos de esta tabla
        //     $fields = [];
        //     $fields1 = [];
        //     $types=[];
        //     // Recorrer las columnas de esta tabla y guardar los nombres de los campos en el array
        //     foreach($tableData["columns"] as $column) {
        //         if (isset($column->Field)) {
        //             $fields[] = $column->Field;
        //         } elseif (isset($column->COLUMN_NAME)) {
        //             $fields[] = $column->COLUMN_NAME;
        //         }

        //         $types[]= $column->Type;
        //     }
        //     foreach($tableData["data"] as $column2) {
        //         // return $column2->idActa;
        //         if (is_object($column2) && isset($column2->idActa)) {
        //             $fields1[] = $column2->idActa;
        //           }

        //     }


        //     // Agregar este array al array de resultados con el nombre de la tabla como clave
        //     $columnas[$tableName] = $fields;
        //     $columnas1[$tableName] = $fields1;
        //     $tipos[$tableName] = $types;
        // }
        // return $columnas1;
        // return  $tipos;
        // return  array_column(session()->get('tablesName')[$tableNames[0]]["columns"], 'Field');

        // @foreach($tablesData as $tableName => $table)
        return view('campo.create',compact('tableNames','contenido','columnas','tipos'));
        
    }

    public function create()
    {

        $tableNames = array_keys(session()->get('tablesName'));

        $contenido=session()->get('tablesName');
        // return  $contenido;
        $columnas = [];
        $columnas1 = [];
        $tipos = [];
        // return $contenido;
        // return $columns;
        // Recorrer cada tabla en el objeto
        foreach($contenido as $tableName => $tableData) {
            // Crear un array para almacenar los nombres de los campos de esta tabla
            $fields = [];
            $fields1 = [];
            $types=[];
            // Recorrer las columnas de esta tabla y guardar los nombres de los campos en el array
            foreach($tableData["columns"] as $column) {
                if (isset($column->Field)) {
                    $fields[] = $column->Field;
                } elseif (isset($column->COLUMN_NAME)) {
                    $fields[] = $column->COLUMN_NAME;
                }

                $types[]= $column->Type;
            }
            foreach($tableData["data"] as $column2) {
                // return $column2->idActa;
                if (is_object($column2) && isset($column2->idActa)) {
                    $fields1[] = $column2->idActa;
                  }

            }


            // Agregar este array al array de resultados con el nombre de la tabla como clave
            $columnas[$tableName] = $fields;
            $columnas1[$tableName] = $fields1;
            $tipos[$tableName] = $types;
        }
        // return $columnas1;
        // return  $tipos;
        // return  array_column(session()->get('tablesName')[$tableNames[0]]["columns"], 'Field');

        // @foreach($tablesData as $tableName => $table)
        return view('campo.create',compact('tableNames','contenido','columnas','tipos'));



        // if (Auth::user()->rol=='Administrativo'){   //boteon registrar

        //     return view('TareaCampo.create');
        // } else{
        //     return redirect()->route('TareaCampo.index')->with('datos','..::No tiene Acceso ..::');
        // }
    }
    public function store(Request $request){
        // return $request;
        $database = Database::latest()->first();
        $data = $request->validate([
            'campo' => 'required',
            'condicion' => 'required',
            'tabla' => 'required',
            'tipoValidar' => 'required',
            'longitud' => 'numeric', 
            'condicion_text' => '', 
            'null' => '', 
        ]);
        
        $data["aux"] = "";
        // return $data;
        // Verifica si condicion_text es un array y no está vacío antes de intentar iterar sobre él
        if (isset($data["condicion_text"]) && is_array($data["condicion_text"])) {
            foreach ($data["condicion_text"] as $key ) {
                $data["aux"] = $data["aux"] . $key . ",";
            }
        }
        if(!isset($array['null'])){
            $data["null"]= 1;
        }
        $data["condicion_text"] =  $data["aux"];
        $data["estado"] = 1;
        $database = Database::latest()->first();
        $nombre=$database->nombre_db;
        $data["baseDatos"]=$nombre;
        $data["fecha"] = date('Y-m-d H:i:s');
        $TareaCampo = TareaCampo::create($data);
        // return $data;
        // $TareaCampo->update(['estado' => 1]);
       
        return redirect()->route('tareacampo.index');
    }

    public function analizar($id,$state)
    {

        $TareaCampo=TareaCampo::findOrFail($id);
        $TareaCampo->update(['estado' => 2]);
        $tableNames = array_keys(session()->get('tablesName'));

        $contenido=session()->get('tablesName');
        // return  $contenido;

        $columnas1 = [];
        $columnas = [];
        $campo=$TareaCampo->campo;
        $condicion_text=[];
        $condicion_text = explode(',', $TareaCampo->condicion_text);
        $condicion_text = array_filter($condicion_text);
      
        $i = 0;

        foreach ($contenido[$TareaCampo->tabla]["data"] as $key) {

            if ($key->$campo=="" && $TareaCampo->null=="0") {
                $columnas[] =  $key;
            }

            else if ($TareaCampo->tipoValidar == "int" && !is_int($key->$campo) && strlen($key->$campo)!=$TareaCampo->longitud) {
                $columnas[] = $key;
            }
            // Validar si el tipo es "decimal" y el valor es un número decimal
            else if ($TareaCampo->tipoValidar == "decimal" && !is_float($key->$campo)) {
                $columnas[] = $key;
            }
            else if ($TareaCampo->tipoValidar == "date" && !\Illuminate\Support\Facades\Validator::make([$campo => $key->$campo], ['campo' => 'date_format:Y-m-d'])->passes()) {
                $columnas[] = $key;
            }
            else if ($TareaCampo->tipoValidar == "time" && !\Illuminate\Support\Facades\Validator::make([$campo => $key->$campo], ['campo' => 'date_format:H:i:s'])->passes()) {
                $columnas[] = $key;
            }
       
            else if($TareaCampo->condicion=="like"&& (strpos($key->$campo, $condicion_text[0])===false)){
                    
                $columnas[] =  $key;
                
            }
            else if($TareaCampo->condicion=="in" ){
                $condicional=false;
                foreach ($condicion_text as $key1 ) {
                    if($key->$campo==$key1){
                        $condicional=true;
                    }
                }
                if(!$condicional){
                    $columnas[] =  $key;
                }
                
            }
            else if($TareaCampo->condicion=="null" && $key->$campo==""){
                // $columnas[$i] = "Null->" . $key;
                $columnas[] =  $key;
            }
            else if (($TareaCampo->condicion==">" || $TareaCampo->condicion=="<") && is_numeric($condicion_text[0])){
                
                // return "aca";
                $condicion = $key->$campo . $TareaCampo->condicion . $condicion_text[0];
                if (!eval("return $condicion;") ) {
                    $columnas[] =  $key;
                } 
            }
        }  

        $tableData =$columnas;
        $columns=$contenido[$TareaCampo->tabla]["columns"];

        $tableName = $TareaCampo->tabla;
        // return $columns[0]->Field;
        // return $tableData;
        if($state==1){
            if (isset($columns[0]->Field)) {
                return view('conexion.show_tableMysql', compact('tableName', 'columns', 'tableData','TareaCampo'));
            }
            else{
                return view('conexion.show_tableSQL', compact('tableName', 'columns', 'tableData','TareaCampo'));
            }
            
        }
        else {
            return view('campo.reporte', compact('tableName', 'columns', 'tableData','TareaCampo'));
        }
       
     
        // // 
    }
    public function pdf($id){
        // analizar($id,0);
        // Assuming you want to pass 1 as the value for $state
        return redirect()->route('analizar.campo', ['id' => $id, 'state' => 0]);

        // return redirect()->route('tareacampo.index');
        // return view('campo.reporte', compact('tableName', 'columns', 'tableData','TareaCampo'));
    }

    public function edit($id)
    {
        $tableNames = array_keys(session()->get('tablesName'));

        $contenido=session()->get('tablesName');
        // return  $contenido;
        $columnas = [];
        $columnas1 = [];
        $tipos = [];
        // return $contenido;
        // return $columns;
        // Recorrer cada tabla en el objeto
        foreach($contenido as $tableName => $tableData) {
            // Crear un array para almacenar los nombres de los campos de esta tabla
            $fields = [];
            $fields1 = [];
            $types=[];
            // Recorrer las columnas de esta tabla y guardar los nombres de los campos en el array
            foreach($tableData["columns"] as $column) {
                if (isset($column->Field)) {
                    $fields[] = $column->Field;
                } elseif (isset($column->COLUMN_NAME)) {
                    $fields[] = $column->COLUMN_NAME;
                }

                $types[]= $column->Type;
            }
            foreach($tableData["data"] as $column2) {
                // return $column2->idActa;
                if (is_object($column2) && isset($column2->idActa)) {
                    $fields1[] = $column2->idActa;
                  }

            }


            // Agregar este array al array de resultados con el nombre de la tabla como clave
            $columnas[$tableName] = $fields;
            $columnas1[$tableName] = $fields1;
            $tipos[$tableName] = $types;
        }

        $TareaCampo=TareaCampo::findOrFail($id);
        $condicion_text=[];
        $condicion_text = explode(',', $TareaCampo["condicion_text"]);
        $condicion_text = array_filter($condicion_text);
        // $condicion_text = $TareaCampo.split(',');
// return  $condicion_text;
        // return $TareaCampo;

        return view('campo.edit',compact('tableNames','contenido','columnas','tipos','TareaCampo','condicion_text'));



        // if (Auth::user()->rol=='Administrativo'){ //boton editar
        //     $TareaCampo=TareaCampo::findOrFail($id);
        //     return view('TareaCampo.edit',compact('TareaCampo'));
        // }else{
        //     return redirect()->route('tareacampo.index')->with('datos','..::No tiene Acceso ..::');
        // }
    }

    public function update(Request $request, $id)
    {
        $data=request()->validate([

        ]);
        $TareaCampo=TareaCampo::findOrFail($id);
        $TareaCampo->apellido_paterno=$request->Apellido1;
        $TareaCampo->apellido_materno=$request->Apellido2;
        $TareaCampo->nombres=$request->nombres;
        $TareaCampo->sexo=$request->sexo;
        $TareaCampo->save();
        return redirect()->route('TareaCampo.index')->with('datos','Registro Actualizado exitosamente...');
    }

    public function destroy($id)
    {
            $TareaCampo=TareaCampo::findOrFail($id);
            $TareaCampo->estado='0';
            $TareaCampo->save();
            return redirect()->route('tareacampo.index')->with('datos','Registro Eliminado..');
    }


    public function confirmar($id){
        if (Auth::user()->rol=='Administrativo'){ //boton eliminar
            $TareaCampo=TareaCampo::findOrFail($id);
            return view('TareaCampo.confirmar',compact('TareaCampo'));
        }else{
            return redirect()->route('TareaCampo.index')->with('datos','..::No tiene Acceso ..::');
        }
    }


    public function cancelar(){
        return redirect()->route('TareaCampo.index')->with('datos','acciona cancelada...');
    }
    public function DniRepetido($dni_comprobar){
        $TareaCampos=TareaCampo::all();
        if(count($TareaCampos)==0){
            return false;
        }else{
            foreach($TareaCampos as $TareaCampo){
                if($TareaCampo->$DNI==$dni_comprobar){
                    return true;
                    break;
                }
            }
            return false;
        }
    }

}
