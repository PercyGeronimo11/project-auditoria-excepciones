<?php

namespace App\Http\Controllers;
// use App\Controllers\DatabaseController;
use App\Models\TareaCampo;
use App\Models\Database;
use App\Http\Controllers\DatabaseController;
// use App\Http\Controllers\array_set();
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
// use App\Models\TareaCampo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TareaCampoController extends Controller
{

    const PAGINATION=7;
    // protected 

    public function index(Request $request){

        // DatabaseController::configureDatabaseConnection1();
        // $db = DB::connection('dynamic');
        // $users = $db->select('SELECT * FROM acta');
        // return $users;
        
        $database = Database::latest()->first(); // Obtener el último registro de la tabla Database
        $nombre=$database->nombre_db;

        $busqueda=$request->get('buscarpor');
        $TareaCampos=TareaCampo::where('tabla','like','%'.$busqueda.'%')
        ->where('estado','<>','0')
        ->where('baseDatos','=',$nombre)
        ->where('bdManager','=',$database->tipo)
        ->paginate($this::PAGINATION);
        // return $TareaCampos->links();
        // $TareaCampos->links("campo.index")
        return view('campo.index', compact('TareaCampos', 'busqueda'));
        
        
    }

    public function create()
    {

       // $tableNames = array_keys(session()->get('tablesName'));
      //  $columnas = session()->get('columnas');
      //  $tipos = session()->get('tipos');
        $tableNames = array_keys(session()->get('tablesName'));
        $contenido=session()->get('tablesName');
        $columnas = [];
        $tipos = [];
        foreach($contenido as $tableName => $tableData) {
            $fields = [];
            $types=[];
            foreach($tableData["columns"] as $column) {
                if (isset($column->Field)) {
                    $fields[] = $column->Field;
                } else {
                    $fields[] = $column['name'];
                }
                if (isset($column->Type)) {
                    $types[]= $column->Type;
                }
                else{
                    $types[]= $column["type"];
                } 
               
            }
            // Agregar este array al array de resultados con el nombre de la tabla como clave
            $columnas[$tableName] = $fields;
            $tipos[$tableName] = $types;
        }

        return view('campo.create',compact('tableNames','columnas','tipos'));


    }

public function store(Request $request){
    // return $request;
    $database = Database::latest()->first();

    $data = $request->validate([
        'campo' => 'required',
        'condicion' => '',
        'tabla' => 'required',
        'tipoValidar' => 'required',
        'longitud' => ['nullable', 'numeric'],
        'condicion_text' => [
            function ($attribute, $value, $fail) use ($request) {
                $condicion = $request->condicion;
                // $fail("te falto llenar contenido en la condicion".$condicion);
                $valor=false;
                foreach ($request->condicion_text as $item ){
                    if($item == null){
                        $valor=true;
                        break;
                    }
                }
                // foreach( => intem)
                if ($valor && $condicion !="1") {
                    $fail("te falto llenar contenido en la condicion");
                }
             
            },
        ],
        'null' => '', 
    ]);
    

    if( $data['condicion'] == "1"){
        $data['condicion']="";
    }
    if( $data['longitud'] = ""){
        $data['longitud']="0";
    }
    $tareas =TareaCampo::where('estado','<>',0)
    ->where('baseDatos','=',$database["nombre_db"])
    ->where('campo','=',$data["campo"] )
    ->where('tabla','=',$data["tabla"] )
    ->get();
    $data["aux"] = "";

    if (isset($data["condicion_text"]) && is_array($data["condicion_text"])) {
        foreach ($data["condicion_text"] as $key ) {
            $data["aux"] = $data["aux"] . $key . ",";
        }
    }
    if(!isset($array['null'])){
        $data["null"]= 1;
    }
    

        // return  "empty" ;
        $data["condicion_text"] =  $data["aux"];
        $data["estado"] = 1;

        $nombre=$database->nombre_db;
        $data["baseDatos"]=$nombre;
        $data["bdManager"]=$database->tipo;
        $data["user"]= Auth::user()->userName;
        $data["fecha"] = date('Y-m-d H:i:s');
        $TareaCampo = TareaCampo::create($data);

       
        return redirect()->route('tareacampo.index');

   
}

    public function analizar($id, $state)
{
    $TareaCampo = TareaCampo::findOrFail($id);
    $TareaCampo->update(['estado' => 2]);


    $contenido = session()->get('tablesName');
    $campo = $TareaCampo->campo;
    $condicion_text = array_filter(explode(',', $TareaCampo->condicion_text));
    $columnas = [];
    $pruebas=[];
    $pruebas1=[];
    $condition =true;

        foreach ($contenido[$TareaCampo->tabla]["data"] as $key) {
            $valorCampo = $key->$campo;
            $TareaCampo->longitud==0 ? $longitudValida =true : $longitudValida =strlen($valorCampo) == $TareaCampo->longitud;
      
            $condicion = $TareaCampo->condicion;
            if (count($condicion_text) > 1) {
                $min = min($condicion_text);
                $max = max($condicion_text);
                $condition = ($valorCampo >= $min && $valorCampo <= $max);
            }
         
            $tipoValidar = $TareaCampo->tipoValidar;
            $validadores = [
                "int" => is_int($valorCampo) && $longitudValida,
                "decimal" => is_float($valorCampo),
                "date" => \Illuminate\Support\Facades\Validator::make([$campo => $valorCampo], ['campo' => 'date_format:Y-m-d'])->passes(),
                "time" => \Illuminate\Support\Facades\Validator::make([$campo => $valorCampo], ['campo' => 'date_format:H:i:s'])->passes(),
                "DNI" => strlen($valorCampo)==8, 
                "email" => \Illuminate\Support\Facades\Validator::make([$campo => $valorCampo], ['campo' => 'email'])->passes(), // Validate email format       
            ];
    
            $condiciones = [
                "like" => strpos($valorCampo, $condicion_text[0]) === false ,
                "in" => !in_array($valorCampo, $condicion_text) ,
                "null" => $valorCampo == "",
                "between" => !$condition,
                
            ];
            if(!array_key_exists($TareaCampo->condicion, $condiciones)){
                $condiciones[$TareaCampo->condicion] = !eval('return $key->$campo '. $TareaCampo->condicion.' $condicion_text[0];');
            }
            
      
            // if($condiciones[$condicion]){
            //     $columnas[] = $key;
            // }
    
            if ($valorCampo == "" && $TareaCampo->null == "0" || !$validadores[$tipoValidar] || $condiciones[$condicion]) {
                $columnas[] = $key;
    
            }
    
            // $pruebas[]="condicion:".$condiciones[$condicion]."campo:".$valorCampo." tipo:".!$validadores[$tipoValidar];
        }


        
        // return $pruebas;
        $tableData = $columnas;
        $columns = $contenido[$TareaCampo->tabla]["columns"];
        $tableName = $TareaCampo->tabla;
        $view = isset($columns[0]->Field) ? 'conexion.show_tableMysql' : 'conexion.show_tableSQL1';
        $view = $state == 1 ? $view : 'campo.reporte';

        if( $state == 0){
            // return "hola";
            $TareaCampo=TareaCampo::findOrFail($id);

            $pdf = pdf::loadView('campo.reporte',['tableName' => $tableName, 'columns' => $columns, 'tableData' => $tableData, 'TareaCampo' => $TareaCampo]);
            $pdfPath = 'pdfs/' . uniqid() . '.pdf';
            $TareaCampo->update(['url_doc' => $pdfPath]);
            // return "cambio";
            // $TareaCampo->update(['url_doc' => $pdfPath]);
            // return $pdfPath;
            Storage::disk('public')->put($pdfPath, $pdf->output());
        


            // if($request->hasFile('curriculum')){
            //     $archivo=$request->file('curriculum')->store('ArchivosCurriculum','public');
            //     $url = Storage::url($archivo);
            //     $Postulacion->curriculum=$url;
            // }



        }

       
    
        return view($view, compact('tableName', 'columns', 'tableData', 'TareaCampo'));

    
   
}



public function generatepdf($id){
    $sequence = Sequence_result::findOrFail($id);
    $pdfPath = $sequence->url_doc;
    if (Storage::disk("public")->exists($pdfPath)) {
        return response()->download(Storage::disk("public")->path($pdfPath));
    } else {
        return response()->json(['error' => 'PDF not found'], 404);
    }
}

    public function pdf($id){
        // analizar($id,0);
        // Assuming you want to pass 1 as the value for $state

        // $database = Database::latest()->first();
        // $data= Sequence_result::create([
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

        // $pdf = pdf::loadView('analizar.campo',['id' => $$id, 'state' => 0]);

        // Storage::disk('public')->put($pdfPath, $pdf->output());
        // $data->update(['url_doc' => $pdfPath]);

        // $TareaCampo=TareaCampo::findOrFail($id);

        // $pdf = pdf::loadView('campo.reporte',['$id' => $resultado_analisis, 'dataGeneral' => $data]);
        // $pdfPath = 'pdfs/' . uniqid() . '.pdf';
        // Storage::disk('public')->put($pdfPath, $pdf->output());
        // $TareaCampo->update(['url_doc' => $pdfPath]);

        return redirect()->route('analizar.campo', ['id' => $id, 'state' => 0]);

        // return redirect()->route('tareacampo.index');
        // return view('campo.reporte', compact('tableName', 'columns', 'tableData','TareaCampo'));
    }
    public function show($id)
    {
        // $tableNames = array_keys(session()->get('tablesName'));
        // $columnas = session()->get('columnas');
        // $tipos = session()->get('tipos');

        // $view = $condicion == 1 ? $view : 'campo.reporte';
        $tableNames = array_keys(session()->get('tablesName'));
        $contenido=session()->get('tablesName');
        $columnas = [];
        $tipos = [];
        foreach($contenido as $tableName => $tableData) {
            $fields = [];
            $types=[];
            foreach($tableData["columns"] as $column) {
                if (isset($column->Field)) {
                    $fields[] = $column->Field;
                } else {
                    $fields[] = $column['name'];
                }
                if (isset($column->Type)) {
                    $types[]= $column->Type;
                }
                else{
                    $types[]= $column["type"];
                } 
               
            }
            // Agregar este array al array de resultados con el nombre de la tabla como clave
            $columnas[$tableName] = $fields;
            $tipos[$tableName] = $types;
        }

        $TareaCampo=TareaCampo::findOrFail($id);
        $condicion_text=[];
        $condicion_text = explode(',', $TareaCampo["condicion_text"]);
        $condicion_text = array_filter($condicion_text);
        $condicion=0;


        return view('campo.edit',compact('tableNames','columnas','tipos','TareaCampo','condicion_text','condicion'));

    }

    public function edit($id)
    {
        // $tableNames = array_keys(session()->get('tablesName'));
        // $columnas = session()->get('columnas');
        // $tipos = session()->get('tipos');

        // $view = $condicion == 1 ? $view : 'campo.reporte';

        $TareaCampo=TareaCampo::findOrFail($id);
        $condicion_text=[];
        $condicion_text = explode(',', $TareaCampo["condicion_text"]);
        $condicion_text = array_filter($condicion_text);
        $condicion=1;

         $tableNames = array_keys(session()->get('tablesName'));
        $contenido=session()->get('tablesName');
        $columnas = [];
        $tipos = [];
        foreach($contenido as $tableName => $tableData) {
            $fields = [];
            $types=[];
            foreach($tableData["columns"] as $column) {
                if (isset($column->Field)) {
                    $fields[] = $column->Field;
                } else {
                    $fields[] = $column['name'];
                }
                if (isset($column->Type)) {
                    $types[]= $column->Type;
                }
                else{
                    $types[]= $column["type"];
                } 
               
            }
            // Agregar este array al array de resultados con el nombre de la tabla como clave
            $columnas[$tableName] = $fields;
            $tipos[$tableName] = $types;
        }

        return view('campo.edit',compact('tableNames','columnas','tipos','TareaCampo','condicion_text','condicion'));

       
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'campo' => 'required',
            // 'condicion' => 'required',
            'condicion' => '',
            'tabla' => 'required',
            'tipoValidar' => 'required',
            // 'longitud' => 'numeric', 
            'condicion_text' => '', 
            'null' => '', 
        ]);
        // return $data;

        if( $data["longitud"] = ""){
            $data["longitud"]="0";
        }
        // return  $tareas ;
        // return $request;
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
        // $database = Database::latest()->first();
        // $nombre=$database->nombre_db;
        // $data["baseDatos"]=$nombre;
        $data["fecha"] = date('Y-m-d H:i:s');
        $TareaCampo=TareaCampo::findOrFail($id);
        $TareaCampo->update($data);
        
        // $TareaCampo->apellido_paterno=$request->Apellido1;
        // $TareaCampo->apellido_materno=$request->Apellido2;
        // $TareaCampo->nombres=$request->nombres;
        // $TareaCampo->sexo=$request->sexo;
        // $TareaCampo->save();
        return redirect()->route('tareacampo.index')->with('datos','Registro Actualizado exitosamente...');
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
        return redirect()->route('tareacampo.index')->with('datos','acciona cancelada...');
    }

    public function analizarback($id,$state)
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
        // $temp=[];
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
       
            else if($TareaCampo->condicion=="like"&& (strpos($key->$campo, $condicion_text[0])===false) && strlen($key->$campo)!=$TareaCampo->longitud){
                    
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
                // if($TareaCampo->tipoValidar=="int"||$TareaCampo->tipoValidar=="DNI"){
                    $condicion = $key->$campo . $TareaCampo->condicion . $condicion_text[0];
                    if (!eval("return $condicion;") ) {
                        $columnas[] =  $key;
                    } 

                }
                else if (($TareaCampo->condicion==">" || $TareaCampo->condicion=="<")){
                 
                    // $fecha1 = new DateTime($key->$campo);
                    // $fecha2 = new DateTime($condicion_text[0]);
                    // if($TareaCampo->tipoValidar=="int"||$TareaCampo->tipoValidar=="DNI"){
                        // $condicion = $fecha1 . $TareaCampo->condicion . $fecha2;
                        $condicionEvaluada = eval('return  $key->$campo '. $TareaCampo->condicion.' $condicion_text[0];');
                        // return $condicionEvaluada;
                        // $condicion = $key->$campo . " " . $TareaCampo->condicion . " " . $condicion_text[0];
                        // return  $condicion ;
                        // return $condicion;
                        if (!$condicionEvaluada) {
                            // $temp[]=$condicion;
                            $columnas[] =  $key;
                        } 
                        // $columnas[] =  $key;
    
                    }
          
               
        }  
    //    return $temp;
        $tableData =$columnas;
        $columns=$contenido[$TareaCampo->tabla]["columns"];

        //return $columns;

        $tableName = $TareaCampo->tabla;
        // return $columns[0]->Field;
        // return $tableData;
        if($state==1){
            if (isset($columns[0]->Field)) {
                return view('conexion.show_tableMysql', compact('tableName', 'columns', 'tableData','TareaCampo'));
            }
            else{
                return view('conexion.show_tableSQL1', compact('tableName', 'columns', 'tableData','TareaCampo'));
            }
            
        }
        else {
            return view('campo.reporte', compact('tableName', 'columns', 'tableData','TareaCampo'));
        }
       
     
        // // 
    }

}
