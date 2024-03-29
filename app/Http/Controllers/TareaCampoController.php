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
        // $busqueda=$request->get('buscarpor');
        // $TareaCampos=TareaCampo::where('Apellido_Paterno','like','%'.$busqueda.'%')
        // ->where('estado','=','1')
        // ->paginate($this::PAGINATION);
        // return session()->get('driverBD');
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
                $fields[] = $column->Field;

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
        return view('campo.index',compact('tableNames','contenido','columnas','tipos'));
    }

    public function create()
    {

        if (Auth::user()->rol=='Administrativo'){   //boteon registrar

            return view('TareaCampo.create');
        } else{
            return redirect()->route('TareaCampo.index')->with('datos','..::No tiene Acceso ..::');
        }
    }

    public function store(Request $request)
    {
        // return  $request;
        
        // return $request->all();
        $tableNames = array_keys(session()->get('tablesName'));

        $contenido=session()->get('tablesName');
        // return  $contenido;

        $columnas1 = [];
        $columnas = [];
        $campo=$request->campo;
        // return $contenido[$request->tabla]["data"][0]->$campo;
        // foreach($contenido as $tableName => $tableData) {
        //     // Crear un array para almacenar los nombres de los campos de esta tabla

        //     $fields1 = [];
        //     $campo=$request->campo;
        //     // Recorrer las columnas de esta tabla y guardar los nombres de los campos en el array

        //     foreach($tableData["data"] as $column2) {
        //         // return $column2->idActa;
        //         if (is_object($column2) && isset($column2->$campo)) {

        //             $fields1[] = $column2->$campo;
        //           }
        //     }



        //     $columnas1[$tableName] = $fields1;

        // }
        $i = 0;
        // return $contenido["data"];
        foreach ($contenido[$request->tabla]["data"] as $key) {
            // return $request->condicion;
            // return strpos($key->$campo, $request->condicion_text);
            //no se pq cambie orden de int y nulll (primero debe comprobar si es numero o no)creoi que encontre error
            //error si pones numero y es un 
            if ($key->$campo=="" && $request->null=="NONULL") {
                $columnas[] =  $key;
            }

            else if ($request->tipoValidar == "int" && !is_int($key->$campo) && strlen($key->$campo)!=$request->longitud) {
                $columnas[] = $key;
            }
            // Validar si el tipo es "decimal" y el valor es un número decimal
            else if ($request->tipoValidar == "decimal" && !is_float($key->$campo)) {
                $columnas[] = $key;
            }
            else if ($request->tipoValidar == "date" && !\Illuminate\Support\Facades\Validator::make([$campo => $key->$campo], ['campo' => 'date_format:Y-m-d'])->passes()) {
                $columnas[] = $key;
            }
            else if ($request->tipoValidar == "time" && !\Illuminate\Support\Facades\Validator::make([$campo => $key->$campo], ['campo' => 'date_format:H:i:s'])->passes()) {
                $columnas[] = $key;
            }
       
            else if($request->condicion=="like"&& (strpos($key->$campo, $request->condicion_text[0])===false)){
                    
                $columnas[] =  $key;
                
            }
            else if($request->condicion=="in" ){
                $condicional=false;
                foreach ($request->condicion_text as $key1 ) {
                    if($key->$campo==$key1){
                        $condicional=true;
                    }
                }
                if(!$condicional){
                    $columnas[] =  $key;
                }
                
            }
            else if($request->condicion=="null" && $key->$campo==""){
                // $columnas[$i] = "Null->" . $key;
                $columnas[] =  $key;
            }
            else if (($request->condicion==">" || $request->condicion=="<") && is_numeric($request->condicion_text[0])){
                
                // return "aca";
                $condicion = $key->$campo . $request->condicion . $request->condicion_text[0];
                if (!eval("return $condicion;") ) {
                    $columnas[] =  $key;
                } 
            }
        }  

        $tableData =$columnas;
        $columns=$contenido[$request->tabla]["columns"];
        // return $columns;
        $tableName = $request->tabla;

        // $columns = DB::connection('dynamic')
        //     ->table('INFORMATION_SCHEMA.COLUMNS')
        //     ->select('TABLE_NAME', 'COLUMN_NAME', 'DATA_TYPE')
        //     ->get();
        // return  $columns ;
        return view('conexion.show_tableMysql', compact('tableName', 'columns', 'tableData'));

        // return $columnas;

        // foreach ($columnas1[$request->tabla] as $key) {
            
        //     // $condicion2 = $key . $request->condicion . $request->condicion_text;
        //     // Evaluar la condición de manera segura
        //     if($request->condicion=="null" && $key==""){
        //             // $columnas[$i] = "Null->" . $key;
        //             $columnas[] =  $i;
             
        //     }
            
        //     else if($request->condicion=="like" && strpos($key, $request->condicion_text) == false){
                
        //         $columnas[] =  $i;
                
        //     }
        //     else if($request->condicion=="in" && strpos($key, $request->condicion_text) == false){
        //         $columnas[] =  $i;
        //     }
        //     else if($request->condicion=="int" && !(is_numeric($key))){
        //         $columnas[] =  $i;
        //     }
        //     else if (is_numeric($request->condicion_text)){
        //         $condicion = $key . $request->condicion . $request->condicion_text;
        //         if (!eval("return $condicion;") ) {
        //             $columnas[] =  $i;
        //         } 
        //     }
        //     $i++;
        // }

            // else if (is_numeric($request->condicion_text)) {
            //     if($request->condicion=="int"){
            //         $columnas[$i] =  $key;
            //     }
            //     $condicion = $key . $request->condicion . $request->condicion_text;
            //     if (eval("return $condicion;") ) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }

            // }
            // if($request->condicion=="int" && !(is_numeric($request->condicion_text))){
            //     $columnas[$i] = $key;
            // }
            // else if (is_numeric($request->condicion_text))
            //     $condicion = $key . $request->condicion . $request->condicion_text;
            //     if (eval("return $condicion;") ) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }
           


            // if($request->condicion=="null"){
            //     if ($request->condicion_text=="") {
            //         $columnas[$i] = "paso" . $key;
            //    }
            //    else {
            //        $columnas[$i] = "NO paso" . $key;
            //    }
            // }
            // if($request->condicion=="int"){
            //     if (is_numeric($request->condicion)) {
            //          $columnas[$i] = "paso" . $key;
            //     }
            //     else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }
            // if (is_numeric($request->condicion)) {
            //     // if($request->condicion=="int"){
            //     //     $columnas[$i] = "paso" . $key;
            //     // }

            //     // Crear la condición
            //     $condicion = $key . $request->condicion . $request->condicion_text;
            //     if (eval("return $condicion;") ) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }
            // else if($request->condicion=="like"){
            //     if (strpos($key, $request->condicion_text) !== false) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }
            // else{
            //     if ($key== $request->condicion_text) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }

            // if($request->condicion == ">"||$request->condicion == "<"){
            //     if (eval("return $condicion;") ) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }
            // else{
            //     if (strpos($key, $request->condicion_text) !== false) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }
            // else{
            //     if (is_numeric($key)) {
            //         $columnas[$i] = "paso" . $key;
            //     } else {
            //         $columnas[$i] = "NO paso" . $key;
            //     }
            // }



        

        // return   $columnas;
            // $data=request()->validate([
            //         ]);
            //         $TareaCampo=new TareaCampo();
            //         $TareaCampo->dni=$request->dni;
            //         $TareaCampo->apellido_paterno=$request->Apellido1;
            //         $TareaCampo->apellido_materno=$request->Apellido2;
            //         $TareaCampo->nombres=$request->nombres;
            //         $TareaCampo->sexo=$request->sexo;
            //         $TareaCampo->estado='1';
            //         $TareaCampo->save();
            //         return redirect()->route('TareaCampo.index')->with('datos','Registrados exitosamente...');
    }

    public function edit($id)
    {
        if (Auth::user()->rol=='Administrativo'){ //boton editar
            $TareaCampo=TareaCampo::findOrFail($id);
            return view('TareaCampo.edit',compact('TareaCampo'));
        }else{
            return redirect()->route('TareaCampo.index')->with('datos','..::No tiene Acceso ..::');
        }
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
            return redirect()->route('TareaCampo.index')->with('datos','Registro Eliminado..');


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
