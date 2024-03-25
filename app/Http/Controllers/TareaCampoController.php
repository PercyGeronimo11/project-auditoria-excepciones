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
        $tipos = [];
        // return $columns;
        // Recorrer cada tabla en el objeto
        foreach($contenido as $tableName => $tableData) {
            // Crear un array para almacenar los nombres de los campos de esta tabla
            $fields = [];
            $types=[];
            // Recorrer las columnas de esta tabla y guardar los nombres de los campos en el array
            foreach($tableData["columns"] as $column) {
                $fields[] = $column->Field;
                $types[]= $column->Type;
            }
        
            // Agregar este array al array de resultados con el nombre de la tabla como clave
            $columnas[$tableName] = $fields;
            $tipos[$tableName] = $types;
        }

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
            $data=request()->validate([
                    ]);
                    $TareaCampo=new TareaCampo();
                    $TareaCampo->dni=$request->dni;
                    $TareaCampo->apellido_paterno=$request->Apellido1;
                    $TareaCampo->apellido_materno=$request->Apellido2;
                    $TareaCampo->nombres=$request->nombres;
                    $TareaCampo->sexo=$request->sexo;
                    $TareaCampo->estado='1';
                    $TareaCampo->save();
                    return redirect()->route('TareaCampo.index')->with('datos','Registrados exitosamente...');
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
